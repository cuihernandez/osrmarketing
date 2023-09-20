
<!-- Modal -->
<div class="modal modal-xl fade" id="modalSystemVoices" tabindex="-1" aria-labelledby="modalSystemVoicesLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="modalSystemVoicesLabel">Check available system voices</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">

                <div class="text-center my-5" id="systemVoicesSpinner">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>				

				<table class="table table-striped table-hover d-none" id="systemVoicesTable">
					<thead class="thead-dark">
					    <tr>
					        <th scope="col">Voice name</th>
					        <th scope="col">Voice code</th>
					        <th scope="col">Actions</th>
					    </tr>
					</thead>
					<tbody>
					</tbody>
				</table>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal modal-xl fade" id="modalGoogleVoices" tabindex="-1" aria-labelledby="modalGoogleVoicesLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="modalGoogleVoicesLabel">Check out Google Premium voices</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">

				<div class="text-center my-5" id="voicesSpinner">
				    <div class="spinner-border" role="status">
				        <span class="visually-hidden">Loading...</span>
				    </div>
				</div>				

				<table class="table table-striped table-hover data-table d-none" id="voicesTable">
					<thead class="thead-dark">
					    <tr>
					        <th scope="col">Voice name</th>
					        <th scope="col">Voice code</th>
					        <th scope="col">Language</th>
					        <th scope="col">Gender</th>
					        <th scope="col">Actions</th>
					    </tr>
					</thead>
					<tbody>
					</tbody>
				</table>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>