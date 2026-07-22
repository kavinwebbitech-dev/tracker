<table class="table table-bordered table-striped">
	<thead>
        <tr>
            <th>Payment Date</th>
            <th>Amount</th>
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
		</tr>
		@endforeach
		<tr>
			<td>Remaining Amount</td>
			<td>{{ $project_details->bid_amount - $ramian_amount }}</td>
		</tr>
	</tbody>
</table>