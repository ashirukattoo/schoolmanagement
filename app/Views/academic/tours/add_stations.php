<?= $this->extend('admin/assets/default') ?>
<?= $this->section('content') ?>
    <div class="container mt-1">
        <div class="card mx-auto shadow-sm">
            <div class="card-header bg-info"><h6 class="mb-0"><?= $pageTitle ?></h6></div>
            <div class="card-body">
                <?php if (session()->getFlashdata('error')) { ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
                    </div>
                <?php }elseif (session()->getFlashdata('success')) { ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-square"></i> <?= session()->getFlashdata('success') ?>
                    </div>
                <?php } ?>
                <form action="<?= base_url('admin/trans/stations/add') ?>" method="post" id='stationForm'>
                    <?= csrf_field() ?>
                    <div id="stationRepeater">
                        <div class="row stationRow mb-3">
                            <div class="col-md-3">
                                <label>Station Name</label>
                                <input type="text" name="station[]" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <label>Region</label>
                                <select name="region[]" class="form-control">
                                    <?php foreach ($regions as $k): ?>
                                        <option value="<?= $k['regID'] ?>"><?= $k['regName'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>District</label>
                                <select name="district[]" class="form-control">
                                    <?php foreach ($districts as $k): ?>
                                        <option value="<?= $k['disID'] ?>"><?= $k['disName'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Ward</label>
                                <select name="ward[]" class="form-control">
                                    <?php foreach ($wards as $k): ?>
                                        <option value="<?= $k['waID'] ?>"><?= $k['waName'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Street</label>
                                <select name="street[]" class="form-control">
                                    <?php foreach ($streets as $k): ?>
                                        <option value="<?= $k['strID'] ?>"><?= $k['strName'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="addRow" class="btn btn-secondary btn-sm mt-2">+ Add More Station</button>
                    <button class="btn btn-sm btn-info w-10">Register</button>
                </form>
            </div>
        </div>
        <div class="card mx-auto mt-3 shadow-sm">
            <div class="card-header bg-info"><h6 class="mb-0">Recoreded Stations</h6></div>
            <div class="card-body">
                <table id="dataTable" class="table datatable table-bordered table-striped table-hover">
                    <thead class="table-secondary">
                        <tr>
                            <th>#</th>
                            <th>Staion Name</th>
                            <th>Ward</th>
                            <th>District</th>
                            <th>Region</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (! empty($recStations)):
                        $count=1;
                     ?>
                        <?php foreach ($recStations as $s): ?>
                            <tr>
                                <td><?= $count; ?></td>
                                <td><?= $s['staName'] ?></td>
                                <td><?= $s['street'].' - '.$s['ward'] ?></td>
                                <td><?= $s['district'] ?></td>
                                <td><?= $s['region']?></td>
                                <td><?= $s['staStatus'] ?? 0?></td>
                            </tr>
                        <?php
                            $count++;
                            endforeach; 
                        ?>
                    <?php else: ?>
                        <tr><td colspan="6">No record found.</td></tr>
                    <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        const BASE_URL = "<?= base_url() ?>";
        document.addEventListener("DOMContentLoaded", function() {
            
            // Add new row
            document.getElementById("addRow").addEventListener("click", function() {
                let original = document.querySelector(".stationRow");
                let clone = original.cloneNode(true);
                // clear inputs
                clone.querySelectorAll("input").forEach(input => input.value = "");
                clone.querySelectorAll("select").forEach(sel => sel.selectedIndex = 0);

                document.getElementById("stationRepeater").appendChild(clone);
            });
            // Remove row
            document.addEventListener("click", function(e) {
                if (e.target.classList.contains("removeRow")) {
                    let row = e.target.closest(".stationRow");
                    let total = document.querySelectorAll(".stationRow").length;
                    // keep at least one row
                    if (total > 1) {
                        row.remove();
                    }
                }
            });

            const form = document.getElementById('stationForm');
            const regionSelect = document.getElementById('regionSelect');
            const districtSelect = document.getElementById('districtSelect');
            const wardSelect = document.getElementById('wardSelect');

            //  Exit safely if form not on this page
            if(!form) return;

            // --- Cascading behavior (page specific) ---
            regionSelect?.addEventListener('change', async function(){
                if(!this.value) return;
                const data = await fetch(`<?= base_url('trans/getDistricts') ?>/${this.value}`).then(r=>r.json());
                districtSelect.innerHTML = `<option value="">--Select District--</option>`;
                data.forEach(d=> districtSelect.innerHTML += `<option value="${d.disID}">${d.disName}</option>`);
                districtWrapper.style.display='block';
            });

            districtSelect?.addEventListener('change', async function(){
                const data = await fetch(`<?= base_url('trans/getWards') ?>/${this.value}`).then(r=>r.json());
                wardSelect.innerHTML = `<option value="">--Select Ward--</option>`;
                data.forEach(w=> wardSelect.innerHTML += `<option value="${w.waID}">${w.waName}</option>`);
                wardWrapper.style.display='block';
            });

            wardSelect?.addEventListener('change', async function(){
                const data = await fetch(`<?= base_url('trans/getStreets') ?>/${this.value}`).then(r=>r.json());
                streetSelect.innerHTML = `<option value="">--Select Street--</option>`;
                data.forEach(s=> streetSelect.innerHTML += `<option value="${s.strID}">${s.strName}</option>`);
                streetWrapper.style.display='block';
            });

            // --- Submit + validation (uses global functions, but runs only here) ---
            form.addEventListener('submit', async function(e){
                e.preventDefault();

                if(!validateForm(form)) return;  // <-- from global file

                const data = await submitFormAjax(form); // <-- from global file
                if(data.status === 'success'){
                    showToast(data.message,'success');
                    form.reset();
                } else {
                    showToast(data.message,'danger');
                }
            });

        });
    </script>


<?= $this->endSection() ?>