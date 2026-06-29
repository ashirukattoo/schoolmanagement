// Toast notification (safe on all pages)
function showToast(message, type='info', delay=3000){
  const toast = document.createElement('div');
  toast.className = `toast align-items-center text-bg-${type} border-0`;
  toast.setAttribute('role','alert');
  toast.innerHTML = `
    <div class="d-flex">
      <div class="toast-body">${message}</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>`;
  const container = document.getElementById('toastContainer');
  if(container){
      container.appendChild(toast);
      const bsToast = new bootstrap.Toast(toast,{delay});
      bsToast.show();
      toast.addEventListener('hidden.bs.toast',()=>toast.remove());
  } else {
      alert(message); // fallback if toast container not present
  }
}

// General required-field validation (returns true/false)
function validateForm(form){
    let valid = true;
    form.querySelectorAll('[required]').forEach(input=>{
        if(!input.value.trim()){
            valid = false;
            showToast(`${input.name} is required`,'warning');
        } else if(input.type === 'email'){
            const re=/^\S+@\S+\.\S+$/;
            if(!re.test(input.value)){
                valid = false;
                showToast('Invalid email format','danger');
            }
        }
    });
    return valid;
}

// Generic AJAX submit (safe on all pages)
async function submitFormAjax(form){
    const formData = new FormData(form);
    try {
        const res = await fetch(form.action, {
            method: form.method || 'POST',
            body: formData,
            headers: { 'X-Requested-With':'XMLHttpRequest' }
        });
        return await res.json();
    } catch (err) {
        showToast('Server error','danger');
        return { status:'error', message:'Server error' };
    }
}
