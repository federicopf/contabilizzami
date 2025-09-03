<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Contracts\Services\UserServiceInterface;
use Illuminate\Http\Request;

class SuperadminUserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $type = $request->query('superadmin', '0');
        $isSuperadmin = $type === '1';
        
        $users = $this->userService->getUsersBySuperadminType($isSuperadmin);
    
        return view('superadmin.users.index', ['users' => $users]);
    }

    public function create()
    {
        return view('superadmin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'superadmin' => 'required|boolean',
        ], [
            'name.required' => 'Il campo nome è obbligatorio.',
            'name.string' => 'Il campo nome deve essere una stringa.',
            'name.max' => 'Il campo nome non può superare i 255 caratteri.',
            'email.required' => 'Il campo Email è obbligatorio.',
            'email.string' => 'Il campo email deve essere una stringa.',
            'email.email' => 'Il campo email deve essere un indirizzo email valido.',
            'email.max' => 'Il campo email non può superare i 255 caratteri.',
            'email.unique' => 'L\'indirizzo email inserito è già in uso.',
            'superadmin.required' => 'Il campo tipo di utente è obbligatorio.',
            'superadmin.boolean' => 'Il campo tipo di utente deve essere un valore valido.',
        ]);
    
        $result = $this->userService->createUser($request->all());
        $user = $result['user'];
        $temporaryPassword = $result['temporary_password'];
    
        return view('superadmin.users.create_success', compact('user', 'temporaryPassword'));
    }
    
    public function show(User $user)
    {
        return view('superadmin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('superadmin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'superadmin' => 'required|boolean',
        ], [
            'name.required' => 'Il campo nome è obbligatorio.',
            'name.string' => 'Il campo nome deve essere una stringa.',
            'name.max' => 'Il campo nome non può superare i 255 caratteri.',
            'email.required' => 'Il campo Email è obbligatorio.',
            'email.string' => 'Il campo email deve essere una stringa.',
            'email.email' => 'Il campo email deve essere un indirizzo email valido.',
            'email.max' => 'Il campo email non può superare i 255 caratteri.',
            'email.unique' => 'L\'indirizzo email inserito è già in uso.',
            'superadmin.required' => 'Il campo tipo di utente è obbligatorio.',
            'superadmin.boolean' => 'Il campo tipo di utente deve essere un valore valido.',
        ]);
    
        $this->userService->updateUser($user, $request->all());
    
        return redirect()->route('superadmin.users.show', $user->id)
            ->with('success', 'Utente aggiornato con successo.');
    }
    
    public function destroy(User $user)
    {
        $this->userService->deleteUser($user);
    
        return redirect()->route('superadmin.users.index')
            ->with('success', 'Utente eliminato con successo.');
    }

    public function resetPassword(User $user)
    {
        $temporaryPassword = $this->userService->resetUserPassword($user);

        return redirect()->route('superadmin.users.show', $user->id)
            ->with('success', 'La password è stata resettata con successo. La nuova password è: ' . $temporaryPassword);
    }
}
