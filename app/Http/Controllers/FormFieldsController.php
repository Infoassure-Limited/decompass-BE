<?php

namespace App\Http\Controllers;

use App\Models\FormField;
use Illuminate\Http\Request;

class FormFieldsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $form_fields = FormField::orderBy('name')->get();
        return response()->json(compact('form_fields'), 200);
    }
    public function store(Request $request)
    {
        $form_fields = json_decode(json_encode($request->form_fields));
        foreach ($form_fields as $field) {
            $form_field = new FormField();
            $form_field->label = $field->label;
            $form_field->placeholder = $field->label;
            $form_field->name = strtolower(str_replace(' ', '_', str_replace('.', '', $field->label)));
            $form_field->input_type = $field->input_type;
            $form_field->is_required = $field->is_required;
            if (isset($field->available_options)) {
                $form_field->available_options = str_replace(',', '~', $field->available_options);
            }
            $form_field->save();
        }
        return response()->json(['message' => 'Successful'], 200);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FormField  $formField
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormField $form_field)
    {
        $form_field->label = $request->label;
        $form_field->placeholder = $request->label;
        $form_field->name = strtolower(str_replace(' ', '_', str_replace('.', '', $request->label)));
        $form_field->input_type = $request->input_type;
        $form_field->is_required = $request->is_required;
        if (isset($request->available_options)) {
            $form_field->available_options = str_replace(',', '~', $request->available_options);
        }
        $form_field->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FormField  $formField
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormField $formField)
    {
        $formField->delete();
        return response()->json([], 204);
    }
}
