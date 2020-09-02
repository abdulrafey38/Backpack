<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\User;
use Bouncer;
use Auth;
use App\Post;
/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController

{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('user', 'users');
        $this->crud->enableExportButtons();


    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        //CRUD::setFromDb(); // columns

         // Columns can be defined using the fluent syntax or array syntax:
        // $this->crud->addColumn(['name' => 'name', 'email' => 'email']);
         CRUD::addColumn(['name' => 'name', 'type' => 'text']);
       //CRUD::addColumn(['email' => 'email', 'type' => 'text']);
        $this->crud->addColumn('email');



    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(UserRequest::class);

       CRUD::setFromDb(); // fields


          //Fields can be defined using the fluent syntax or array syntax:

       //   CRUD::addField(['name' => 'name', 'type' => 'text','placeholder'=>'Enter name']);


    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function store(UserRequest $request)
    {

        $input = $request->all();
        $input['password']=\bcrypt($request->password);
        $user =User::create($input);
        Bouncer::allow('editor')->to('update', \App\Post::class);
        $user->assign('editor');
        \Alert::success(trans('backpack::crud.insert_success'))->flash();
        return \redirect('/admin/user');
    }

   


    public function update(UserRequest $request,$id)
    {
        $user = User::findorFail($id);


        $input = $request->all();
        $input['password']=\bcrypt($request->password);
        $user->update($input);
        \Alert::success(trans('backpack::crud.insert_success'))->flash();
        return \redirect('/admin/user');
    }
}
