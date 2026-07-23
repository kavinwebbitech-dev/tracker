<h5>Task Name: {{ $update_task->name ?? '' }}</h5>

<div class="row">
    <div class="col-md-12">
        <form action="{{ route('sub_admin.freelancer.request.payment.update') }}" method="post">
            @csrf
            <div class="row">
                <input type="hidden" name="client_task_id" value="{{ $update_task->id ?? '' }}">
            </div>

            <div class="row">
                <!-- Already Paid -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Already Paid</label>
                        <input type="number" step="0.01" 
                               class="form-control @error('already_paid') is-invalid @enderror" 
                               name="already_paid" 
                               value="{{ old('already_paid', $update_task->paid_amount ?? '0') }}" 
                               placeholder="Enter amount" readonly>
                        @error('already_paid')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <!-- Pending Paid -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Pending Paid</label>
                        <input type="number" step="0.01" 
                               class="form-control @error('pending_paid') is-invalid @enderror" 
                               name="pending_paid" 
                               value="{{ $update_task->total_amount - $update_task->paid_amount }}" 
                               placeholder="Enter amount" min="1" max="{{ $update_task->total_amount - $update_task->paid_amount }}">
                        @error('pending_paid')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Total Hours</label>
                        <input type="number" step="0.01" 
                               class="form-control @error('total_hours') is-invalid @enderror" 
                               name="total_hours" 
                               value="{{ old('total_hours', $update_task->total_hours ?? '') }}" 
                               placeholder="Enter hours">
                        @error('total_hours')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Total Hours -->
                

                <!-- Cost Type -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Cost Type <span class="text-danger">*</span></label>
                        <select name="cost_type" class="form-select @error('cost_type') is-invalid @enderror" disabled>
                            <option value="">-- Select Type --</option>
                            <option value="hourly" {{ $update_task->cost_type == 'hourly' ? 'selected' : '' }}>Hourly</option>
                            <option value="fixed" {{ $update_task->cost_type == 'fixed' ? 'selected' : '' }}>Fixed</option>
                        </select>
                        @error('cost_type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div> 
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Pay Amount</label>
                        <input type="number" step="1" 
                            class="form-control @error('amount') is-invalid @enderror" 
                            name="amount" 
                            value="" min="1" max="{{ $update_task->total_amount - $update_task->paid_amount }}"
                            placeholder="Enter amount">
                        @error('amount')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-2">
                    <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                </div>
            </div>

        </form>
    </div>
</div>
