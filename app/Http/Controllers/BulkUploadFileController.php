<?php

namespace App\Http\Controllers;

use App\Models\BulkUploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Illuminate\Support\Facades\Validator;

class BulkUploadFileController extends Controller
{
    public function upload(Request $request)
    {
        $user = $this->getUser();
        $client = $this->getClient();
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,xls,xlsx|max:2048',
            'type' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $type = $request->type;
        $file = $request->file('file');
        $filename = $type . '_' . time() . '_' . $file->getClientOriginalName();
        $path = Storage::disk('public')->putFileAs('uploads', $file, $filename);
        // $path = Storage::disk('spaces')->putFileAs('uploads', $file, $filename);

        if ($file->getClientOriginalExtension() === 'csv') {
            $csv = Reader::createFromPath($file->getPathname(), 'r');
            $csv->setHeaderOffset(0);
            $columns = $csv->getHeader();
            $data = iterator_to_array($csv->getRecords());
        } else {
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();
            $columns = array_shift($data);
        }

        $fileRecord = BulkUploadFile::create([
            'client_id' => $client->id,
            'user_id' => $user->id,
            'type' => $type,
            'filename' => $filename,
            'path' => $path,
            'columns' => $columns,
            'data' => $data,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'File uploaded', 'file_id' => $fileRecord->id], 201);
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
