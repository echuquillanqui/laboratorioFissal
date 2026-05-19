<?php
namespace App\Livewire\Laboratory;
use App\Enums\LaboratoryResultEstado;use App\Models\Laboratory\LaboratoryOrder;use App\Models\Laboratory\LaboratoryResult;use Illuminate\Support\Facades\Auth;use Livewire\Component;
class LaboratoryResultManager extends Component{ public ?int $orderId=null; public array $results=[];
public function mount(){ $this->orderId=request()->integer('order'); if($this->orderId){$this->loadOrder();}}
public function loadOrder(){ $order=LaboratoryOrder::with('items.test.options','items.result')->findOrFail($this->orderId); foreach($order->items as $item){$r=$item->result; $this->results[$item->id]=['texto'=>$r->resultado_texto??null,'numerico'=>$r->resultado_numerico??null,'opcion'=>$r->resultado_opcion??null,'observacion'=>$r->observacion??null,'estado'=>$r->estado??LaboratoryResultEstado::PENDIENTE->value]; }}
public function savePartial(){foreach($this->results as $itemId=>$data){LaboratoryResult::updateOrCreate(['laboratory_order_item_id'=>$itemId],['resultado_texto'=>$data['texto']??null,'resultado_numerico'=>$data['numerico']??null,'resultado_opcion'=>$data['opcion']??null,'observacion'=>$data['observacion']??null,'estado'=>LaboratoryResultEstado::REGISTRADO->value]);}}
public function validateFinal(){foreach($this->results as $itemId=>$data){LaboratoryResult::where('laboratory_order_item_id',$itemId)->update(['estado'=>LaboratoryResultEstado::VALIDADO->value,'validado_por'=>Auth::id(),'fecha_validacion'=>now()]);}}
public function render(){return view('livewire.laboratory.result-manager',['orders'=>LaboratoryOrder::with('patient:id,name')->latest()->get(['id','patient_id','fecha_orden']),'order'=>$this->orderId?LaboratoryOrder::with('items.test.options','items.result')->find($this->orderId):null]);}
}
