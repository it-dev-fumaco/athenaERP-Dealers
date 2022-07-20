<form action="/edit_warehouse_location" method="post">
    @csrf
    @foreach ($warehouses as $warehouse)
        <div class="form-group row">
            <label for="location" class="col-12 col-form-label">{{ $warehouse->warehouse }}</label>
            <div class="col-12">
                <input type="text" name="location[]" class="form-control" value="{{ $warehouse->location }}" placeholder="Location">
                <input type="text" name="warehouses[]" class="d-none" value="{{ $warehouse->warehouse }}" readonly>
            </div>
        </div>
    @endforeach
    <input type="text" name="item_code" value="{{ $item_code }}" hidden readonly>
    <button class="btn btn-primary float-right" type="submit">Submit</button>
  </form>