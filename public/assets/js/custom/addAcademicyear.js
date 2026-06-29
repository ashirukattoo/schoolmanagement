$(document).ready(function(){
    //Open assignTourOnRoute Model
    $('#tourAddVehicle').on('click', function(){
        $('#assignTourOnRouteModel').modal('show');
    });
    $('#btndismiss').on('click', function(){
        $('#assignTourOnRouteModel').modal('hide');
    });

   //Open Examination Model
    $('#addExam').on('click', function(){
        $('#examModel').modal('show');
    });
    $('#btndismiss').on('click', function(){
        $('#examModel').modal('hide');
    });

    //Open Academic Year Model
    $('#addAcademiYear').on('click', function(){
        $('#ayModal').modal('show');
    });
    $('#btndismiss').on('click', function(){
        $('#ayModal').modal('hide');
    });

    // Save Accademic Year
    $('#academicYear').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: '<?= base_url("admin/save/ac_year") ?>',
            type: 'post',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response){
                if(response.status === 'success'){
                    $('#ayModal').modal('hide');
                    alert(response.message);
                } else {
                    alert(response.message);
                }
            }, 
            error: function(){
                alert('Error connecting to server');
            }
        });
    });

    // When Region changes → Load Districts
    $(document).on("change", "select[name='region[]'], select[name=region]", function() {
        let regionID = $(this).val();
        let districtDropdown = $(this).closest('.stationRow').find("select[name='district[]']");
        let wardDropdown = $(this).closest('.stationRow').find("select[name='ward[]']");
        let streetDropdown = $(this).closest('.stationRow').find("select[name='street[]']");

        districtDropdown.html("<option value=''>Loading...</option>");
        wardDropdown.html("<option value=''>--Select Ward--</option>");
        streetDropdown.html("<option value=''>--Select Street--</option>");

        $.getJSON(BASE_URL + "/ajax/get-districts/" + regionID, function(data){
            districtDropdown.empty().append("<option value=''>--Select District--</option>");
            $.each(data, function(i, item){
                districtDropdown.append("<option value='"+item.disID+"'>"+item.disName+"</option>");
            });
        });
    });

    // When District changes → Load Wards
    $(document).on("change", "select[name='district[]'], select[name=district]", function() {
        let districtID = $(this).val();
        let wardDropdown = $(this).closest('.stationRow').find("select[name='ward[]']");
        let streetDropdown = $(this).closest('.stationRow').find("select[name='street[]']");

        wardDropdown.html("<option value=''>Loading...</option>");
        streetDropdown.html("<option value=''>--Select Street--</option>");

        $.getJSON(BASE_URL + "/ajax/get-wards/" + districtID, function(data){
            wardDropdown.empty().append("<option value=''>--Select Ward--</option>");
            $.each(data, function(i, item){
                wardDropdown.append("<option value='"+item.waID+"'>"+item.waName+"</option>");
            });
        });
    });

    // When Ward changes → Load Streets
    $(document).on("change", "select[name='ward[]'], select[name=ward]", function() {
        let wardID = $(this).val();
        let streetDropdown = $(this).closest('.stationRow').find("select[name='street[]']");

        streetDropdown.html("<option value=''>Loading...</option>");

        $.getJSON(BASE_URL + "/ajax/get-streets/" + wardID, function(data){
            streetDropdown.empty().append("<option value=''>--Select Street--</option>");
            $.each(data, function(i, item){
                streetDropdown.append("<option value='"+item.strID+"'>"+item.strName+"</option>");
            });
        });
    });

    // Academic Year Cahange → Load Terms
    $(document).on("change", "select[name='year'], select[name=year]", function() {
        let ayID = $(this).val();
        let termsDropdown = $(this).closest('.yearRow').find("select[name='terms']");

        termsDropdown.html("<option value=''> Loading...</option>");

        $.getJSON(BASE_URL + "/ajax/get-terms/" + ayID, function(data){
            termsDropdown.empty().append("<option value=''>--Select Terms--</option>");
            $.each(data, function(i, item){
                termsDropdown.append("<option value='"+item.tID+"'>"+item.tName+"</option>");
            });
        });
    });

});