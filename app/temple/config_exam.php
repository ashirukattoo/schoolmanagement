<?= $this->extend('templates/default'); ?>
<?= $this->section('content'); ?>

<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h5>Configure Examination</h5>
    </div>

    <div class="card-body">
      <form id="examForm" action="<?= base_url('register/add_exam'); ?>" method="post">

        <!-- Level Selection -->
        <div class="row mb-3">
          <div class="col-md-4">
            <label for="level" class="form-label">Select Level</label>
            <select id="level" name="level" class="form-select" required>
              <option value="">-- Select Level --</option>
              <option value="1">Ordinary Level</option>
              <option value="2">Advanced Level</option>
            </select>
          </div>

          <div class="col-md-4">
            <label for="academic_year" class="form-label">Academic Year</label>
            <select id="academic_year" name="academic_year" class="form-select" required>
              <option value="">-- Select Year --</option>
            </select>
          </div>

          <div class="col-md-4">
            <label for="term" class="form-label">Term</label>
            <select id="term" name="term_id" class="form-select" required>
              <option value="">-- Select Term --</option>
            </select>
          </div>
        </div>

        <!-- Exam Name and Dates -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Examination Name</label>
            <input type="text" class="form-control" name="name" placeholder="Enter Exam Name" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Start Date</label>
            <input type="date" class="form-control" name="start_date" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">End Date</label>
            <input type="date" class="form-control" name="end_date" required>
          </div>
        </div>

        <!-- Subject Configuration -->
        <div id="subjectsContainer" class="mt-4" style="display:none;">
          <h6 class="mb-3 text-primary">Select Subjects to Include in Exam</h6>

          <table class="table table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th>Select</th>
                <th>Code</th>
                <th>Subject</th>
                <th>Paper</th>
                <th>Include Practical?</th>
                <th>Optional?</th>
              </tr>
            </thead>
            <tbody id="subjectTableBody"></tbody>
          </table>
        </div>

        <div class="mt-4">
          <label class="form-label">Exam Status</label>
          <select name="status" class="form-select w-auto d-inline-block" required>
            <option value="Next">Next</option>
            <option value="Progress">Progress</option>
            <option value="Done">Done</option>
            <option value="Cancelled">Cancelled</option>
          </select>

          <button type="submit" class="btn btn-success float-end">Save Examination</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ===== JavaScript Logic ===== -->
<script>
const subjectList = [
  {code: '011', name: 'Civics', paper: 1, practical: false, optional: false},
  {code: '012', name: 'History', paper: 1, practical: false, optional: false},
  {code: '013', name: 'Geography', paper: 1, practical: false, optional: false},
  {code: '015', name: 'Elimu ya Dini ya Kiislamu', paper: 1, practical: false, optional: false},
  {code: '021', name: 'Kiswahili', paper: 1, practical: false, optional: false},
  {code: '022', name: 'English Language', paper: 1, practical: false, optional: false},
  {code: '024', name: 'Literature in English', paper: 1, practical: false, optional: false},
  {code: '031/1', name: 'Physics', paper: 1, practical: true, optional: false},
  {code: '031/2', name: 'Physics', paper: 2, practical: true, optional: false},
  {code: '032/1', name: 'Chemistry', paper: 1, practical: true, optional: false},
  {code: '032/2', name: 'Chemistry', paper: 2, practical: true, optional: false},
  {code: '033/1', name: 'Biology', paper: 1, practical: true, optional: false},
  {code: '033/2', name: 'Biology', paper: 2, practical: true, optional: false},
  {code: '041', name: 'Basic Mathematics', paper: 1, practical: false, optional: false},
];

// Mock cascading data
const academicYears = {
  1: ['2025', '2026'], // O-Level
  2: ['2025'], // A-Level
};

const terms = {
  '2025': ['Term I', 'Term II'],
  '2026': ['Term I', 'Term II']
};

document.getElementById('level').addEventListener('change', function() {
  const levelId = this.value;
  const yearSelect = document.getElementById('academic_year');
  yearSelect.innerHTML = '<option value="">-- Select Year --</option>';
  if (academicYears[levelId]) {
    academicYears[levelId].forEach(y => {
      yearSelect.innerHTML += `<option value="${y}">${y}</option>`;
    });
  }
});

document.getElementById('academic_year').addEventListener('change', function() {
  const year = this.value;
  const termSelect = document.getElementById('term');
  termSelect.innerHTML = '<option value="">-- Select Term --</option>';
  if (terms[year]) {
    terms[year].forEach((t, i) => {
      termSelect.innerHTML += `<option value="${i + 1}">${t}</option>`;
    });
  }
});

document.getElementById('term').addEventListener('change', function() {
  const tbody = document.getElementById('subjectTableBody');
  tbody.innerHTML = '';
  document.getElementById('subjectsContainer').style.display = 'block';
  subjectList.forEach((s, index) => {
    tbody.innerHTML += `
      <tr>
        <td><input type="checkbox" name="subjects[${index}][selected]" value="1"></td>
        <td><input type="hidden" name="subjects[${index}][code]" value="${s.code}">${s.code}</td>
        <td><input type="hidden" name="subjects[${index}][name]" value="${s.name}">${s.name}</td>
        <td><input type="number" name="subjects[${index}][paper]" value="${s.paper}" class="form-control form-control-sm" style="width:80px"></td>
        <td><input type="checkbox" name="subjects[${index}][include_practical]"></td>
        <td><input type="checkbox" name="subjects[${index}][option]" value="1"></td>
      </tr>
    `;
  });
});
</script>

<?= $this->endSection(); ?>