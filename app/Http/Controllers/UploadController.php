<?php namespace App\Http\Controllers;

use Kris\LaravelFormBuilder\FormBuilderTrait;

class UploadController extends Controller
{
    use FormBuilderTrait;

    public function Upload()
    {
        // Note the trait used. FormBuilder class can be injected if wanted.
        $form = $this->form('App\Forms\UploadForm', [
            'method' => 'POST',
            'route' => action('FileController@uploadNewBalancesFile')
        ]);

        return view('Upload', compact('form'));
    }

}