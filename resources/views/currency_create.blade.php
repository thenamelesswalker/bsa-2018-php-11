@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form role="form" method="POST" action="{{ route('lot.add') }}">
                    @csrf
                    <div class="form-group">
                        <label for="currency">Currency</label>
                        <input type="text" class="form-control" id="currency" name="currency"
                               value="{{ old('currency') }}">
                        @if ($errors->has('currency'))
                            <span class="alert alert-danger">{{ $errors->first('currency') }}</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="text" class="form-control" id="price" name="price" value="{{ old('price') }}">
                        @if ($errors->has('price'))
                            <span class="alert alert-danger">{{ $errors->first('price') }}</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="openDate">Open date</label>
                        <input type="text" class="form-control" id="openDate" name="openDate"
                               value="{{ old('openDate') }}">
                        @if ($errors->has('openDate'))
                            <span class="alert alert-danger">{{ $errors->first('openDate') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="closeDate">Close date</label>
                        <input type="text" class="form-control" id="closeDate" name="closeDate"
                               value="{{ old('closeDate') }}">
                        @if ($errors->has('closeDate'))
                            <span class="alert alert-danger">{{ $errors->first('closeDate') }}</span>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>

            </div>
        </div>
    </div>
@endsection






