<form action="{{ route('sub_admin.projects.payment.edit.update') }}" method="post">
    @csrf
    <div class="row">
        <input type="hidden" name="payment_id" value="{{ $amount_details->id ?? '' }}">
        <div class="col-md-12">
            <div class="form-group">
                <div class="input-group mb-3">
                    <input type="date" class="form-control @error('fld_payment_date') is-invalid @enderror" name="fld_payment_date" id="fld_payment_date" value="{{ $amount_details->fld_payment_date ?? '' }}" required>
                </div>
                @error('fld_payment_date')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <div class="input-group mb-3">
                    <input type="text" class="form-control @error('fld_project_amount') is-invalid @enderror" name="fld_project_amount" id="fld_project_amount" value="{{ $amount_details->fld_project_amount ?? '' }}" required>
                </div>
                @error('fld_project_amount')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-md-2">
            <input type="submit" name="submit" class="btn btn-primary" value="Submit">
        </div>
    </div>
</form>