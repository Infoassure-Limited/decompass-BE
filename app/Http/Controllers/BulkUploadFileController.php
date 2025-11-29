<?php

namespace App\Http\Controllers;

use App\Models\BulkUploadFile;
use App\Models\BusinessProcess;
use App\Models\BusinessUnit;
use App\Models\NDPA\PersonalDataAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;



class BulkUploadFileController extends Controller
{
    public function upload(Request $request)
    {
        $user = $this->getUser();
        $client = $this->getClient();
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xls,xlsx|max:2048',
            'type' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $type = $request->type;
        $identifiers = ['Name'];
        if (isset($request->identifiers)) {
            $identifiers = $request->identifiers;
        }
        $file = $request->file('file');
        // $path = Storage::disk('spaces')->putFileAs('uploads', $file, $filename);
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, true, true, true);
        $columns = array_shift($data);
        $columns = array_map('strtoupper', $columns);
        $columns = array_map('trim', $columns);

        // Detect Dropdowns (Data Validation)
        $dropdowns = [];

        // iterate all cells and read their data validation (Cell::getDataValidation())
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

        set_time_limit(600);
        for ($row = 1; $row <= $highestRow; $row++) {
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $coord = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . $row;
                $cell = $sheet->getCell($coord);
                $validation = $cell->getDataValidation();
                if ($validation !== null && $validation->getType() === DataValidation::TYPE_LIST) {
                    $cellCoordinate = $cell->getCoordinate();
                    $formula = $validation->getFormula1();

                    // If the validation formula is an explicit quoted list ("a,b,c")
                    if (is_string($formula) && (strpos($formula, '"') === 0 || strpos($formula, "'") === 0)) {
                        $options = explode(',', trim($formula, "\"'"));
                    } else {
                        // If the validation references a range (e.g. Sheet1!$A$1:$A$5), read those cell values
                        $options = [];
                        if (is_string($formula)) {
                            // Remove any sheet reference
                            if (strpos($formula, '!') !== false) {
                                $parts = explode('!', $formula);
                                $range = $parts[1];
                            } else {
                                $range = $formula;
                            }

                            $range = str_replace(['$'], '', $range);
                            if (strpos($range, ':') !== false) {
                                list($start, $end) = explode(':', $range);
                                $startCol = preg_replace('/[0-9]+/', '', $start);
                                $startRow = preg_replace('/[^0-9]/', '', $start);
                                $endCol = preg_replace('/[0-9]+/', '', $end);
                                $endRow = preg_replace('/[^0-9]/', '', $end);
                                $startColIdx = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($startCol);
                                $endColIdx = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($endCol);
                                for ($r = (int)$startRow; $r <= (int)$endRow; $r++) {
                                    for ($c = $startColIdx; $c <= $endColIdx; $c++) {
                                        $vCoord = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c) . $r;
                                        $v = $sheet->getCell($vCoord)->getValue();
                                        if ($v !== null && $v !== '') {
                                            $options[] = $v;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if (!empty($options)) {
                        $dropdowns[$cellCoordinate] = $options;
                    }
                }
            }
        }

        if (empty($columns)) {
            return response()->json(['message' => 'Headers are empty!!!. The first row of your file should be for headers, identifying each column'], 500);
        }
        $response = $this->populateDBData($type, $identifiers, $columns, $data, $dropdowns);
        if ($response != 'success') {
            return response()->json(['message' => $response], 500);
        }

        $fileRecord = $this->performBulkUpload($client, $user, $type, $columns, $data, $dropdowns);
        set_time_limit(300);

        return response()->json(['message' => 'File uploaded', 'file_id' => $fileRecord->id], 201);
        
    }

    private function populateDBData($type, $identifiers, $columns, $fileData, $dropdowns) 
    {
        $client = $this->getClient();
        
        $unit_name = 'UNIT NAME';
        $bus_unit = 'BUSINESS UNIT';
        $proc_name = 'PROCESS NAME';
        $proc_owner = 'PROCESS OWNER';
        $bus_process = 'BUSINESS PROCESS';
        $PDI = 'PERSONAL DATA ITEM';
        switch ($type) {
            case 'business_units':
                if(in_array($unit_name, $columns)) {
                    $name_index = array_search($unit_name, $columns);
                    $counter = 0;
                    foreach ($fileData as $data) {
                        $name = $data[$name_index];
                        BusinessUnit::firstOrCreate([
                            'client_id' => $client->id,
                            'group_name' => $name,
                            'unit_name' => $name,
                        ], [
                            'columns' => $columns,
                            'data' => $data,
                            'access_code' => randomcode(), // helper: app/Http/helpers.php
                            'prepend_risk_no_value' => acronym($name), // helper
                            'dropdowns' => $dropdowns[$counter],
                        ]);

                        $counter++;
                    }
                    return 'success';
                } else {
                        return "'$unit_name' header keyword is required on the spreadsheet!";
                }

                case 'business_processes':
                    if(in_array($bus_unit, $columns) && in_array($proc_name, $columns) && in_array($proc_owner, $columns)) {
                        $bu_index = array_search($bus_unit, $columns);
                        $name_index = array_search($proc_name, $columns);                        
                        $po_index = array_search($proc_owner, $columns);
                        $counter = 0;
                        foreach ($fileData as $data) {
                            $name = $data[$name_index];
                            $business_unit = BusinessUnit::firstOrCreate([
                                'client_id' => $client->id,
                                'group_name' => $data[$bu_index],
                                'unit_name' => $data[$bu_index],
                            ], [
                                'access_code' => randomcode(), // helper: app/Http/helpers.php
                                'prepend_risk_no_value' => acronym($data[$bu_index]), // helper
                            ]);
                            BusinessProcess::firstOrCreate([
                                
                                'client_id' => $client->id,
                                'business_unit_id' => $business_unit->id,
                                'name' => $name,
                                ], 
                                ['generated_process_id' => $business_unit->id . '.' . $business_unit->next_process_id,
                                'process_owner' => $data[$po_index], 
                                'columns' => $columns,
                                'data' => $data,
                                'dropdowns' => $dropdowns[$counter],
                            ]);

                            $counter++;
                            $business_unit->increment('next_process_id');
                        }
                        return 'success';
                    }else {
                        return "'$bus_unit', '$proc_name' and '$proc_owner' header keywords are required on the spreadsheet!";
                    }
                case 'pda':
                    if(in_array($bus_unit, $columns) && in_array($bus_process, $columns) && in_array($PDI, $columns)) {
                        $bu_index = array_search($bus_unit, $columns);
                        $bp_index = array_search($bus_process, $columns);
                        $name_index = array_search($PDI, $columns);
                        $counter = 0;
                        foreach ($fileData as $data) {
                            $name = $data[$name_index];
                            $business_unit = BusinessUnit::firstOrCreate([
                                'client_id' => $client->id,
                                'group_name' => $data[$bu_index],
                                'unit_name' => $data[$bu_index],
                            ], [
                                'access_code' => randomcode(), // helper: app/Http/helpers.php
                                'prepend_risk_no_value' => acronym($data[$bu_index]), // helper
                            ]);
                            $business_process = BusinessProcess::firstOrCreate([
                                
                                'client_id' => $client->id,
                                'business_unit_id' => $business_unit->id,
                                'name' => $data[$bp_index],
                            ], ['generated_process_id' => $business_unit->id . '.' . $business_unit->next_process_id,]);
                            PersonalDataAssessment::firstOrCreate([
                                    'client_id' => $client->id,
                                    'business_unit_id' => $business_unit->id,
                                    'business_process_id' => $business_process->id,
                                    'personal_data_item' => $name,
                                ],
                        [
                                'columns' => $columns,
                                'data' => $data,
                                'dropdowns' => $dropdowns,
                            ]);

                            $counter++;
                        }
                        return 'success';
                    } else {
                        return "'$bus_unit', '$bus_process' and '$PDI' header keywords are required on the spreadsheet!";
                    }
            
            default:
                # code...
                break;
        }
        

    }

    

    private function performBulkUpload($client, $user, $type, $columns, $data, $dropdowns) {
        return BulkUploadFile::updateOrCreate([
                'client_id' => $client->id,
                'user_id' => $user->id,
                'type' => $type
            ],
                [
                // 'filename' => $filename,
                // 'path' => $path,
                'columns' => $columns,
                'data' => $data,
                'dropdowns' => $dropdowns,
                'status' => 'pending',
            ]);
    }

    public function index(Request $request)
    {
        // $user = $this->getUser();
        $client = $this->getClient();
        $files = BulkUploadFile::where('client_id', $client->id)->get();
        return response()->json($files);
    }

    public function show(BulkUploadFile $file)
    {
        $client = $this->getClient();
        if ($file->client_id !== $client->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return response()->json($file);
    }

    public function update(Request $request, BulkUploadFile $file)
    {
        $validator = Validator::make($request->all(), [
            'data' => 'required|array',
            'status' => 'required|in:done,in_progress,processing,pending',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $client = $this->getClient();
        if ($file->client_id !== $client->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $file->update([
            'data' => $request->data,
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'File updated']);
    }

    public function export(Request $request, BulkUploadFile $file)
    {
        $client = $this->getClient();
        if ($file->client_id !== $client->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $format = $request->query('format', 'csv');
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->fromArray($file->columns, null, 'A1');
        // Set data
        $sheet->fromArray($file->data, null, 'A2');

        $filename = $file->filename . '_' . time();
        if ($format === 'xlsx') {
            $writer = new Xlsx($spreadsheet);
            $filename .= '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        } else {
            $writer = new Csv($spreadsheet);
            $filename .= '.csv';
            header('Content-Type: text/csv');
        }

        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
}
