<?php

namespace App\Http\Controllers\Admin\Instruction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Instruction;

class InstructionControllers extends Controller
{
    public function index()
    {
        $data = Instruction::query()->orderBy('id', 'desc')->paginate(20);
        return view('admin.instruction.index', ['instructiondata' => $data]);
    }
    public function create()
    {
        return view('admin.instruction.create');
    }

    public function submit(Request $request)
    {
        $instruction_insert = new Instruction;
        $instruction_insert->section_name           = $request->input('setion_name');
        $instruction_insert->message                = $request->input('message');
        $instruction_insert->status                 = $request->input('status');
        $instruction_insert->save();
        return redirect('admin/instruction')->with('msg', 'Instruction Added Successfully!');
    }
    public function edit($id = 0)
    {
        $user = auth()->guard('webadmin')->user();
        
        $instructionDetails = Instruction::findOrFail($id);
        return view('admin.instruction.edit')->with(compact(['instructionDetails']));
    }
    public function update(Request $request, $id)
    {
        Instruction::where('id', $id)
            ->update([
                'section_name'          => $request->input('setion_name'),
                'message'               => $request->input('message'),
                'status'                => $request->input('status')
                ]);            
        return redirect('admin/instruction')->with('msg', 'Instruction has been updated');
    }
    public function destroy($id)
    {
        Instruction::where('id', $id)->delete();
        return redirect('admin/instruction')->with('msg', 'Instruction deleted successfully');
    }
}
