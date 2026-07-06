<?php
namespace App\Livewire\Products;
use App\Models\Product;
use App\Models\Numerator;
use Livewire\Component;
class ProductForm extends Component {
    public ?Product $product = null;
    public $code=''; public $name=''; public $description=''; public $numerator_id=null;
    public $calculator_class = 'App\\Services\\ProductCalculators\\PropertyCalculator';
    public $config_json = '{}';
    public function mount($id=null){
        if($id){ $this->product = Product::findOrFail($id);
            $this->code=$this->product->code; $this->name=$this->product->name;
            $this->description=$this->product->description; $this->numerator_id=$this->product->numerator_id;
            $this->calculator_class=$this->product->calculator_class;
            $this->config_json = json_encode($this->product->config_json, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
    }
    public function save(){
        $this->validate(['code'=>'required','name'=>'required','calculator_class'=>'required']);
        $cfg = json_decode($this->config_json, true) ?? [];
        $p = $this->product ?? new Product();
        $p->fill(['code'=>$this->code,'name'=>$this->name,'description'=>$this->description,'numerator_id'=>$this->numerator_id?:null,'calculator_class'=>$this->calculator_class,'config_json'=>$cfg,'is_active'=>true]);
        $p->save();
        return redirect()->route('products.index');
    }
    public function render(){ return view('livewire.products.form', ['numerators'=>Numerator::all()]); }
}
