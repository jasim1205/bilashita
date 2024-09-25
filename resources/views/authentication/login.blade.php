@extends('master')

@section('content')
    <div class="col-7 right-side mx-auto" style="padding: 0">
        <div class="form p-4">
            <div class="bg-white rounded shadow-lg p-3">
                @if(Session::has('response'))
                {!!Session::get('response')['message']!!}
                @endif
                <p>Login</p>
                <hr />
                <div class="mx-auto my-3 px-lg-5 w-75">
                    <form action="{{route('login.check')}}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="exampleInputEmail1" name="mobile" aria-describedby="emailHelp"/>
                            {{--  <div id="emailHelp" class="form-text">
                                We'll never share your mobile with anyone else.
                            </div>  --}}
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" name="password"/>
                        </div>
                        <div class="mb-3 form-check">
                            <input  type="checkbox" class="form-check-input" id="exampleCheck1"/>
                            <label class="form-check-label" for="exampleCheck1">Check me out</label>
                        </div>
                        <div class="d-flex ">
                            <button type="submit" class="submit shadow m-1 p-2 btn btn-primary">{{__('Log in')}}</button>
                            <a class="submit shadow m-1 py-2 btn btn-success" href="{{ route('register') }}">{{__('Registration')}}</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection
