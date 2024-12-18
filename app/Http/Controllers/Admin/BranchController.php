<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BranchRequest;
use App\Http\Requests\Admin\BranchUpdateRequest;
use App\DataTables\Admin\BranchDataTable;
use App\Enums\StatusEnum;
use App\Models\Branch;
use Brian2694\Toastr\Facades\Toastr;
use App\Repositories\Interfaces\BranchInterface;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    protected $branch;

    public function __construct(BranchInterface $branch){
        $this->branch  = $branch;
    }

    public function index(BranchDataTable $dataTable, Request $request)
    {
        $branches = $this->branch->paginate();
        return $dataTable->render('admin.branch.index', compact('branches'));
    }

    public function create()
    {
        $users    = $this->branch->allUsers()->where('user_type', 'staff');
        return view('admin.branch.create', compact('users'));
    }

    public function store(BranchRequest $request)
    {
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));
            return back();
        }
        try {
            if ($this->branch->store($request)):
                return redirect()->route('admin.branch')->with('success', __('created_successfully'));
            endif;
        } catch (\Exception $e){
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        }
    }

    public function edit($id)
    {
        $branch     = $this->branch->get($id);
        $users      = $this->branch->allUsers()->where('user_type', 'staff');

        return view('admin.branch.edit', compact('users', 'branch'));
    }

    public function update(BranchUpdateRequest $request)
    {
        if (isDemoMode()) {
            Toastr::error(__('this_function_is_disabled_in_demo_server'));
            return back();
        }
        try {
            if($this->branch->update($request)):
                return redirect()->route('admin.branch')->with('success', __('updated_successfully'));
            endif;
        } catch (\Exception $e){
            return back()->with('danger', __('something_went_wrong_please_try_again'));
        }
    }

    public function delete($id)
    {
        if (isDemoMode()) {
            $success[0] = __('this_function_is_disabled_in_demo_server');
            $success[1] = 'error';
            $success[2] = __('oops');
            return response()->json($success);
        }

        try {
            $branch = $this->branch->get($id);

            if ($branch->default) {
                $success[0] = __('default_branch_can_not_be_possible_to_delete');
                $success[1] = 'error';
                $success[2] = __('oops');
                return response()->json($success);
            }

            if ($this->branch->delete($id)) {
                $success[0] = __('deleted_successfully');
                $success[1] = 'success';
                $success[2] = __('deleted');
                return response()->json($success);
            }

        } catch (\Exception $e) {
            $success[0] = __('something_went_wrong_please_try_again');
            $success[1] = 'error';
            $success[2] = __('oops');
            return response()->json($success);
        }
    }


    public function statusChange(Request $request)
    {
        if (isDemoMode()) {
            $message = __('this_function_is_disabled_in_demo_server');
            return response()->json([
                'status' => 404,
                'message' => $message,
            ]);
        }

        try {
            $branch = $this->branch->get($request->id); // Assuming there's a get() method in the repository

            // Check if the branch is the default branch and active
            if ($branch->default && $branch->status == StatusEnum::ACTIVE) {
                $message = __('default_branch_can_not_be_possible_to_deactivate');
                return response()->json([
                    'status' => 404,
                    'message' => $message,
                ]);
            }

            // Proceed with status change
            $status = $this->branch->statusChange($request);

            if ($status == true) {
                $success = __('updated_successfully');
                return response()->json([
                    'status' => 200,
                    'message' => $success,
                ]);
            }

        } catch (\Exception $e) {
            $message = __('something_went_wrong_please_try_again');
            return response()->json([
                'status' => 404,
                'message' => $message,
            ]);
        }
    }


    public function defaultBranch(Request $request)
    {
        if (isDemoMode()) {
            $message = __('this_function_is_disabled_in_demo_server');
            return response()->json([
                'status' =>500,
                'message'=>$message,
            ]);
        }
        try {
            $status = $this->branch->changeDefault($request);

            if($status == true){
                $success = __('updated_successfully');
                return response()->json([
                    'status' =>200,
                    'message'=>$success,
                ]);
            }
        } catch (\Exception $e){
            $message = __('something_went_wrong_please_try_again');
            return response()->json($message,404);
        }
    }
}
