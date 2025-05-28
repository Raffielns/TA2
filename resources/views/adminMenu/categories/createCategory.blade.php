@extends('layouts.admin')

@section('main-content')
    <div class="container px-5 my-5">
        <h3 class="mb-5">Add Product</h3>
        <form method="POST" action="{{ route('category.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="categoryName">Product Name</label>
                <input class="form-control" id="categoryName" name="categoryName" type="text" placeholder="Category Name"
                    required />
            </div>
            <div class="d-grid">
                <button class="btn btn-primary" id="submitButton" type="submit">Submit</button>
            </div>
        </form>
    </div>
@endsection
