<div class="col-md-12">
    <div class="form-group">
        <label class="form-label">Customers Name</label>
        <div class="input-group in-bord mb-3">
            <input type="text" class="form-control @error('fld_name') is-invalid @enderror" id="fld_name" name="fld_name" placeholder="Customers Name" value="{{ $UserDetails->fld_name }}" readonly>
            <!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="form-group">
        <label class="form-label">Email Address</label>
        <div class="input-group in-bord mb-3">
            <input type="email" class="form-control @error('fld_email') is-invalid @enderror" id="fld_email" name="fld_email" placeholder="Email Address" value="{{ $UserDetails->fld_email }}" readonly>
            <!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="form-group">
        <label class="form-label">Phone Number</label>
        <div class="input-group in-bord mb-3">
            <input type="text" class="form-control @error('fld_phone') is-invalid @enderror" id="fld_phone" name="fld_phone" placeholder="Phone Number" value="{{ $UserDetails->fld_phone }}" readonly>
            <!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
        </div>
    </div>
</div>


<div class="col-md-12">
    <div class="form-group">
        <label class="form-label">Address</label>
        <div class="input-group in-bord mb-3">
            <input type="text" class="form-control @error('fld_address') is-invalid @enderror" id="fld_address" name="fld_address" placeholder="Address" value="{{ $UserDetails->fld_address }}" readonly>
            <!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="form-group">
        <label class="form-label">Company Name</label>
        <div class="input-group in-bord mb-3">
            <input type="text" class="form-control @error('fld_company_name') is-invalid @enderror" id="fld_company_name" name="fld_company_name" placeholder="Company Name" value="{{ $UserDetails->fld_company_name }}" readonly>
            <!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="form-group">
        <label class="form-label">GST Number</label>
        <div class="input-group in-bord mb-3">
            <input type="text" class="form-control @error('fld_customer_gstno') is-invalid @enderror" id="fld_customer_gstno" name="fld_customer_gstno" placeholder="GST Number" value="{{ $UserDetails->fld_customer_gstno }}" readonly>
            <!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
        </div>
    </div>
</div>