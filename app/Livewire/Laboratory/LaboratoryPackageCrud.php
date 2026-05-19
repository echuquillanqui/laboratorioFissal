<?php
namespace App\Livewire\Laboratory;
use App\Models\Laboratory\LaboratoryArea;
use App\Models\Laboratory\LaboratoryPackage;
use App\Models\Laboratory\LaboratoryProfile;
use App\Models\Laboratory\LaboratoryTest;
use Livewire\Component;
use Livewire\WithPagination;
class LaboratoryPackageCrud extends Component{use WithPagination; public string $search=''; public string $testSearch=''; public ?int $selectedAreaId=null; public ?int $editingId=null; public string $nombre=''; public ?string $descripcion=null; public float $precio=0; public bool $estado=true; public array $test_ids=[]; public array $profile_ids=[];
protected function rules(){return ['nombre'=>'required|max:150|unique:laboratory_packages,nombre,'.($this->editingId??'NULL').',id'];}

public function toggleTest(int $testId){if(in_array($testId,$this->test_ids,true)){$this->test_ids=array_values(array_diff($this->test_ids,[$testId]));return;} $this->test_ids[]=$testId;$this->test_ids=array_values(array_unique(array_map('intval',$this->test_ids)));}
public function removeTest(int $testId){$this->test_ids=array_values(array_diff($this->test_ids,[$testId]));}
public function clearTestFilters(){ $this->testSearch=''; $this->selectedAreaId=null; }
public function edit($id){$p=LaboratoryPackage::with('items')->withTrashed()->findOrFail($id);$this->editingId=$id;$this->nombre=$p->nombre;$this->descripcion=$p->descripcion;$this->precio=(float)$p->precio;$this->estado=(bool)$p->estado;$this->test_ids=$p->items->where('tipo_item','test')->pluck('reference_id')->all();$this->profile_ids=$p->items->where('tipo_item','profile')->pluck('reference_id')->all();}
public function save(){ $this->validate(); $p=LaboratoryPackage::updateOrCreate(['id'=>$this->editingId],['nombre'=>$this->nombre,'descripcion'=>$this->descripcion,'precio'=>$this->precio,'estado'=>$this->estado]); $p->items()->delete(); foreach(array_unique($this->test_ids) as $id){$p->items()->create(['tipo_item'=>'test','reference_id'=>$id]);} foreach(array_unique($this->profile_ids) as $id){$p->items()->create(['tipo_item'=>'profile','reference_id'=>$id]);} $this->reset(['editingId','nombre','descripcion','test_ids','profile_ids']);$this->precio=0;$this->estado=true; }
public function delete($id){LaboratoryPackage::findOrFail($id)->delete();} public function restore($id){LaboratoryPackage::withTrashed()->findOrFail($id)->restore();}
public function render(){return view('livewire.laboratory.package-crud',['packages'=>LaboratoryPackage::withTrashed()->with('items')->when($this->search!=='',fn($q)=>$q->where('nombre','like',"%{$this->search}%"))->paginate(10),'tests'=>LaboratoryTest::where('estado',true)->with('area:id,nombre')->when($this->selectedAreaId,fn($q)=>$q->where('laboratory_area_id',$this->selectedAreaId))->when($this->testSearch!=='',fn($q)=>$q->where(function($i){$i->where('nombre','like',"%{$this->testSearch}%")->orWhere('codigo','like',"%{$this->testSearch}%");}))->orderBy('nombre')->get(['id','nombre','codigo','laboratory_area_id']),'selectedTests'=>empty($this->test_ids)?collect():LaboratoryTest::whereIn('id',$this->test_ids)->orderBy('nombre')->get(['id','nombre']),'areas'=>LaboratoryArea::where('estado',true)->orderBy('nombre')->get(['id','nombre']),'profiles'=>LaboratoryProfile::where('estado',true)->get(['id','nombre'])])->layout('layouts.app');}}
