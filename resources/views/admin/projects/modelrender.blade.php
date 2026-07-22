<table class="table table-bordered table-striped">
	<thead>
        <tr>
            <th>Payment Date</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>
    </thead>
	<tbody>
		<?php
			$ramian_amount = 0;
		?>
		@foreach ($amount_details as $key => $value1)
		<?php
			$ramian_amount += $value1->fld_project_amount;
		?>
		<tr>
			<td>{{ date('d-m-Y', strtotime($value1->fld_payment_date)) }}</td>
			<td>{{ $value1->fld_project_amount }}</td>
			<td>
			    <p style="display:flex;">
                   <a href="javascript:void(0);" onclick="deleteProject('{{ route('admin.projects.payment.delete', $value1->id) }}')" class="delete btn btn-danger" style="padding: 4px 12px;margin: 0px 4px;width: 40px;"><i class="ti-trash"></i></a>
                </p>
			</td>
		</tr>
		@endforeach
		<tr>
			<td>Remaining Amount</td>
			<td>{{ $project_details->bid_amount - $ramian_amount }}</td>
			<td></td>
		</tr>
	</tbody>
</table>