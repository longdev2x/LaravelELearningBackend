<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Models\CourseType;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use Encore\Admin\Tree;

class CourseTypeController extends AdminController
{
    //actually for showing tree form of the menus
    public function index(Content $content){
        $tree = new Tree(new CourseType);
        return $content->header('Course Types')
        ->body($tree);
    }

    //Just for view
    protected function detail($id)
    {

        $show = new Show(CourseType::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('description', __('Description'));
        $show->field('order', __('Order'));
        $show->field('order', __('Order'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated_at'));

        return $show;
    }

    //It get's called when you create a new form or edit a row or info
    protected function form()
    {
        dd("create or edit");
        $form = new Form(new CourseType());
        $form->select('parent_id', __('Parent Category'))->options((new CourseType())::selectOptions());
        $form->text('title', __('Title'));
        $form->textarea('description', __('Description'));
        $form->number('Order', __('Order'));
        
        return $form;
    }
}
