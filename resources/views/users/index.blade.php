@foreach($users as $user)
    {{ $user->name }}
    {{ $user->email }}
    {{ $user->password }}
    {{ $user->role }}



@endforeach