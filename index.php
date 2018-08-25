<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
    <title>PHP CRUD</title>
</head>
<body>
<header class="rounded bg-dark p-3 text-warning m-3">
    <h2>PHP CRUD</h2>
</header>

<div class="container">
    <div class="modal fade text-dark" id="contact-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center" id="contactModalTitle">Add New</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label><b>Country Name:</b></label>
                            <input type="text" class="form-control" id="country-name">
                        </div>
                        <div class="form-group">
                            <label><b>Short Description:</b></label>
                            <input type="text" class="form-control" id="short-desc">
                        </div>
                        <div class="form-group">
                            <label><b>Long Description:</b></label>
                            <textarea name="message" rows="4" class="form-control" id="long-desc"></textarea>
                        </div>
                        <input type="hidden" id="country-id" value="0">
                    </form>
                </div>
                <div class="modal-footer">
                    <input type="submit" id="manage-btn" class="btn btn-outline-dark btn-block" onclick="saveCountry('insert');" value="Add Country">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade text-dark" id="view-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h4 class="modal-title text-center" id="contactModalTitle">Country Details</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success p-2 rounded">
                        <h6><b>Country Name:</b></h6><p id="veiw-country"></p>
                    </div>
                    <div class="alert alert-danger p-2 rounded">
                        <h6><b>Short Description:</b></h6><p id="veiw-short-desc"></p>
                    </div>
                    <div class="alert alert-primary p-2 rounded">
                        <h6><b>Long Description:</b></h6><p id="veiw-long-desc"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h2>MySQL Table Manager</h2>
            <input type="button" value="Add New" data-toggle="modal" data-target="#contact-modal"
                   class="btn btn-success float-md-right">
            <br><br>
            <table class="table table-hover table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Country Name</th>
                    <th>Options</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/dataTables.bootstrap4.min.js"></script>
<script>

    $(document).ready(function () {
        getExistingData(0, 10);
    });

    function saveCountry(action) {
        var countryName = $('#country-name');
        var shortDesc = $('#short-desc');
        var longDesc = $('#long-desc');
        var countryId = $('#country-id');

        if (checkEmpty(countryName) && checkEmpty(shortDesc) && checkEmpty(longDesc)) {
            $.ajax({
                url: 'create.php',
                type: 'POST',
                data: {
                    countryName: countryName.val(),
                    shortDesc: shortDesc.val(),
                    longDesc: longDesc.val(),
                    countryId: countryId.val(),
                    action:action
                },
                error: function () {
                    alert('Error while inserting data');
                },
                success: function (data) {
                    alert(data);
                    $('#country-name').val('');
                    $('#short-desc').val('');
                    $('#long-desc').val('');
                    $('#country-id').val(0);
                    $('#manage-btn').attr('value','Add Country').attr('onclick',"saveCountry('insert')");
                    $('#contact-modal').modal('hide');
                    $('tbody').html('');
                    getExistingData(0,10);
                }
            });
        }

    }

    function checkEmpty(selector) {
        if (selector.val() == '') {
            selector.css('border', '1px solid red');
            return false;
        } else {
            selector.css('border', '1px solid green');
            return true;
        }
    }

    function getExistingData(start, limit) {
        $.ajax({
            url: 'create.php',
            type: 'POST',
            data: {
                start: start,
                limit: limit,
                getdata: 'get data'
            },
            error: function () {
                alert('Error while fetching data');
            },
            success: function (data) {
                if (data!='reachedMax') {
                    $('tbody').append(data);
                    start += limit;
                    getExistingData(start, limit);
                } else {
                    $('.table').DataTable();
                }
            }
        });

    }

    function editCountry(data) {
        $.ajax({
            url: 'create.php',
            type: 'POST',
            dataType: 'json',
            data: {
                countryid: data
            },
            error: function () {
                alert('Error while fetching data');
            },
            success: function (data) {
                $('#country-name').val(data.countryName);
                $('#short-desc').val(data.shortDesc);
                $('#long-desc').val(data.longDesc);
                $('#country-id').val(data.countryId);
                $('#manage-btn').attr('value','Save Changes').attr('onclick',"saveCountry('update')");
                $('#contact-modal').modal('show');
            }
        });

    }

    function deleteCountry(data) {
        $.ajax({
            url: 'create.php',
            type: 'POST',
            data: {
                deleteid: data
            },
            error: function () {
                alert('Error while fetching data');
            },
            success: function (data) {
                alert(data);
                $('tbody').html('');
                getExistingData(0,10);
            }
        });
    }

    function viewCountry(data) {
        $.ajax({
            url: 'create.php',
            type: 'POST',
            dataType: 'json',
            data: {
                countryid: data
            },
            error: function () {
                alert('Error while fetching data');
            },
            success: function (data) {
                $('#veiw-country').html(data.countryName);
                $('#veiw-short-desc').html(data.shortDesc);
                $('#veiw-long-desc').html(data.longDesc);
                $('#view-modal').modal('show');
            }
        });
    }

</script>
</body>
</html>