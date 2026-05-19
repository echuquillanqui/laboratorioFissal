<?php
namespace App\Livewire\Laboratory;
use App\Models\Laboratory\LaboratoryProfile;
use App\Models\Laboratory\LaboratoryTest;
use Livewire\Component;use Livewire\WithPagination;
class LaboratoryProfileCrud extends Component{use WithPagination; public string $search=''; public ?int $editingId=null; public string $nombre=''; public ?string $descripcion=null; public bool $estado=true; public array $test_ids=[];
protected function rules(){return ['nombre'=>'required|max:150|unique:laboratory_profiles,nombre,'.($this->editingId??'NULL').',id','test_ids'=>'required|array|min:1','test_ids.*'=>'exists:laboratory_tests,id'];}
public function edit($id){$p=LaboratoryProfile::with('tests')->withTrashed()->findOrFail($id);$this->editingId=$id;$this->nombre=$p->nombre;$this->descripcion=$p->descripcion;$this->estado=(bool)$p->estado;$this->test_ids=$p->tests->pluck('id')->all();}
public function save(){ $this->validate(); $p=LaboratoryProfile::updateOrCreate(['id'=>$this->editingId],['nombre'=>$this->nombre,'descripcion'=>$this->descripcion,'estado'=>$this->estado]);$p->tests()->sync($this->test_ids);$this->reset(['editingId','nombre','descripcion','test_ids']);$this->estado=true; }
public function delete($id){LaboratoryProfile::findOrFail($id)->delete();} public function restore($id){LaboratoryProfile::withTrashed()->findOrFail($id)->restore();}
public function render(){return view('livewire.laboratory.profile-crud',['profiles'=>LaboratoryProfile::withTrashed()->with('tests:id,nombre')->when($this->search!=='',fn($q)=>$q->where('nombre','like',"%{$this->search}%"))->orderByDesc('id')->paginate(10),'tests'=>LaboratoryTest::where('estado',true)->orderBy('nombre')->get(['id','nombre'])]);}}
