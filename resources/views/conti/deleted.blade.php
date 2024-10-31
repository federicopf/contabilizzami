@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Conti Eliminati</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($deletedAccounts->isEmpty())
        <p>Non ci sono conti eliminati.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nome Conto</th>
                    <th>Tipo</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deletedAccounts as $account)
                    <tr>
                        <td>{{ $account->name }}</td>
                        <td>{{ $account->getTypeNameAttribute() }}</td>
                        <td>
                            <!-- Pulsante per ripristinare il conto -->
                            <form action="{{ route('conti.restore', $account->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-primary">Ripristina</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
