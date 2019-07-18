<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepositoryInterface;
use App\http\Resources\User as UserResource;

class UserController extends Controller
{
	protected $user;

	/**
     * UserController constructor.
     *
     * @param UserRepositoryInterface $user
     */
    public function __construct(UserRepositoryInterface $user)
    {
        $this->user = $user;
        $this->storeRules = [
			'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'username' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
		];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get list of all users & return collection of Users as a resource
        return UserResource::collection($this->user->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        if ($request->isMethod('put')) {
        	$id = $input['id'];
        } else {
            $this->storeRules['password'] = ['required', 'string', 'min:8'];
            $this->storeRules['confirm_password'] = ['required', 'same:password'];
        	array_push($this->storeRules['email'], 'unique:users');
            unset($input['id']);
        }

        $validator = Validator::make($input, $this->storeRules);

		if ($validator->fails()) {
			return new UserResource(['error' => $validator->errors()]);
		}

        if (isset($id)) {
        	if($this->user->update($id, $input)) {
        		$data = $this->user->get($id);
        	} else {
        		return new UserResource([ 'message' => "unable to save record."]);
        	}
        } else {
            $input['password'] = Hash::make($input['password']);
        	$data = $this->user->create($input);
        	$data['token'] = $data->createToken('AppName')->accessToken;
        }

        return new UserResource($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // fetch user by id
        $data = $this->user->get($id);
        if ($data != null) {
            // return user as a resource
            return new UserResource($data);
        } else {
            // return error as a resource if the user object is null
            return new UserResource([ 'message' => 'unable to fetch user']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // fetch user by id
        $data = $this->user->get($id);
        if ($data != null) {
            // delete and return user as a resource
            $this->user->delete($id);
            return new UserResource($data);
        } else {
            // return error as a resource if the user object is null
            return new UserResource(['message' => 'unable to delete record']);
        }
    }

    /**
     * Delete multiple user entry.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteMultiple(Request $request)
    {
		$validator = Validator::make($request->all(), [
			'ids' => 'required'
        ]);
        
        // validate if ids are present
		
		if (!$validator->fails())
		{
            $ids = $request->input('ids');
            $users = $this->user->getMultiple($ids);
            if ($this->user->deleteMultiple($ids)) {
                // return user resource if deleted
                return UserResource::collection($users);
            } else {
                // return unable to delete error if users are not present
                return new UserResource(['message' => 'unable to delete record/s']);
            }
		} else {
            // return validation error/s as a resource
            return new UserResource(['error' => $validator->errors()]);
        }
    }
}
