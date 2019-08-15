$(document).ready(function() {
    
    //dinamic_content_height();
    add_to_archive();
    add_new_package();
    scan_barcode();
    command_order();
    edit_order();
    edit_package();
    add_new_order();
    pack_select();
    to_order();
    //download();
    key_editor();
    date_sort();
    add_new_user();
    save_to_db_2table();
    returnPackage();
    delPackege();
    
});

function delPackege() {
    $('.delete-package').click(function() {
        if (confirm('Delete this package and all his orders?')) {
            
            var package = $(this).parent().parent().attr('package_id');
            var tr = $(this).parent().parent();
            var result = JSON.stringify(package);
            
            $.ajax({

                url: '/admin/delete_package', 
                type: 'POST', 
                cache: false,
                data: {'json_data': result},
                dataType: 'json',
                success: function (data) {

                    if (data['status']) {

                        alert('Package was deleted');
                        
                        $(tr).animate({'opacity': 0}, 1000, function() {
                            $(this).remove();
                        });
                        
                    } else {
                        
                        alert('Package NOT was deleted. Try again.');
                        
                    }

                }

            });

        } 
        return false;
    });
}

function save_to_db_2table() {
    
    $('.save-to-db').click(function() {
        
        var tr = $(this).parent().parent();
        
        var saving = {
            invoice: $(tr).find('.invoice').text(),
            stamping: $(tr).find('.stamping').text(),
            partnumber: $(tr).find('.partnumber').text(),
            ordernumber: $(tr).find('.ordernumber').text(),
            lils: $(tr).find('.lils input').val(),
            difflils: $(tr).find('.difflils').text(),
            notes: $(tr).find('.notes input').val(),
            tool: $('.tool').text(),
            id: $(tr).find('.id').text(),
            quantity: $(tr).find('.quantity').text()
        }
        
        var result = JSON.stringify(saving);
        //alert(result);
        $.ajax({

            url: '/admin/saving_invoices', 
            type: 'POST', 
            cache: false,
            data: {'json_data': result},
            dataType: 'json',
            success: function (data) {
                
                if (data['status']) {
                    
                    alert('Invoice was saved to data base.');
                    
                    if (!data['excelent']) {
                        $(tr).find('.difflils').text(data['difflils']);
                        $(tr).find('.notes input').val(data['notes']);
                        $(tr).find('.lils input').val('');
                        $(tr).find('.author').text(data['author']);
                    } else {
                        $(tr).css({'background-color': 'rgba(0, 128, 0, 0.2)'}).hide(1500, function() {
                            $(this).remove();
                        });
                    }

                    
                } else {
                    
                    alert('Invoice was not saved to data base...');
                    
                }
                  
            }

        });
        
    });
    
}

function returnPackage() {
    
    $('.return').click(function() {
        
        var package = $(this).parent().parent().attr('package_id');
        var tr = $(this).parent().parent();
        var result = JSON.stringify(package);
        
        $.ajax({

            url: '/admin/out_archive', 
            type: 'POST', 
            cache: false,
            data: {'json_data': result},
            dataType: 'json',
            success: function (data) {
                
                if (data['status']) {
                    
                    alert('Package left the archive');
                    
                    $(tr).animate({'opacity': 0}, 1000, function() {
                        $(this).remove();
                    });
                    
                } else {
                    
                    alert('Package not left the archive');
                    
                    
                    
                }
                  
            }

        });
        
        return false;
        
    });
    
}

function add_to_archive() {
    $('.add-to-archive').click(function() {
        
        var package = $(this).attr('package');
        
        var result = JSON.stringify(package);
        
        var tr = $(this).parent().parent();
        
        $.ajax({

            url: '/admin/to_archive', 
            type: 'POST', 
            cache: false,
            data: {'json_data': result},
            dataType: 'json',
            success: function (data) {
                
                if (data['status']) {
                    
                    alert('Package was added to archive.');
                    
                    $(tr).animate({'opacity': 0}, 1000, function() {
                        $(this).remove();
                    });
                    
                } else {
                    
                    alert('Package was NOT added to archive.');
                    
                    
                    
                }
                  
            }

        });
        
        return false;
    });
}

function add_new_user() {
    
    $('#add-new-user').click(function() {
        
        if ($('#name').val() == '' || $('#key').val() == '') {
            
            alert('Fields is empty.');
            
        } else {
        
        var user = {
            
            login: $('#name').val(),
            key: $('#key').val(),
            rights: $('#rights').val()
            
        }
        
        var result = JSON.stringify(user);
        
        $.ajax({

            url: '/admin/new_user', 
            type: 'POST', 
            cache: false,
            data: {'json_data': result},
            dataType: 'json',
            success: function (data) {
                
                if (data['status']) {
                    
                    alert('New user was added.');
                    location.href = location.pathname;
                    
                } else {
                    
                    alert('New user not added.');
                    
                }
                  
            }

        });
            
        }
        
    });
    
}

function date_sort() {
    
    $('input[type=date]').change(function() {
        
        var date1 = $('input[type=date]:eq(0)').val();
        var date2 = $('input[type=date]:eq(1)').val();
        
        $.ajax({

            url: '/admin/packages', 
            type: 'POST', 
            cache: false,
            data: {'date-1': date1, 'date-2': date2},
            dataType: 'html',
            success: function (data) {
                
                var table = $(data).find('#content-box table');
                
                $('#content-box').html(table);
                  
            }

        });
        
    });
    
}

function key_editor() {
    
    $('#generate-key').click(function() {
        
        $.ajax({

            url: '/admin/generate_key', 
            type: 'POST', 
            cache: false,
            data: {},
            dataType: 'json',
            success: function (data) {
                
                $('#key').val(data['key']);    
                  
            }

        });
        
    });
    
    $('#save-key').click(function() {
    
        var user = {
            
            id: $('#key-id').val(),
            key: $('#key').val()
            
        }
        
        var result = JSON.stringify(user);
        
       $.ajax({

            url: '/admin/save_key', 
            type: 'POST', 
            cache: false,
            data: {'json_data': result},
            dataType: 'json',
            success: function (data) {
                
                if (data['status']) { 
                    
                    alert('New key is saved');
                    
                } else {
                    
                    alert('New key is not saved. Try again.');
                    
                }
                  
            }

        });
        
    });
    
}

/*function download() {
    
    $('#download').click(function() {
        
        var url = $(this).attr('href');
        
        $.ajax({

            url: url, 
            type: 'POST', 
            cache: false,
            data: {},
            dataType: 'html',
            success: function (data) {
                
                var table = $(data).find('table');
                
          
                
                tableToExcel(table, 'name', 'new-file.xls');
                
            }

        });
    
       return false;
        
    });
    
}*/

function to_order() {
    
    $('.link tr:not(#package-table tr:first)').click(function() {
        
        location.href = '/admin/orders/' + $(this).attr('package_id');
        
    });
    
}

function pack_select() {
    
    var url = location.pathname.split('/');
    
    $('#sorting a[sort=' + url[3] + ']').css({'background-color': 'orange'});
    
}

function command_order() {
    
    $('.delete').click(function() {
        
        var tr = $(this).parent();
        
        var id = $(tr).attr('order_id');
        
        $.ajax({

            url: '/admin/delete_order', 
            type: 'POST', 
            cache: false,
            data: {'id': id},
            success: function (data) {
                
                if (data == true) {
                    
                    $(tr).hide(500);
                    
                } else {
                    
                    alert('Order was not delete.');
                    
                }
                  
            }

        });
        
    });
    
    $('.print').click(function() {
        
        
        
        /*var order = {
            
            order_id: $(this).parent().attr('order_id')
            
        }
        
        var result = JSON.stringify(order);
        
        $.ajax({

            url: '/admin/get_barcode', 
            type: 'POST', 
            cache: false,
            data: {'json_data': result},
            dataType: 'json',
            success: function (data) {
                
                if (data == false) {
                    
                    alert('Не удалось получить штрихкод.');
                    
                } else {
                    
                    window.open('http://barcode.local/admin/print/' + );
                    
                    $('#printed-div table img').attr('src', 'https://barcode.tec-it.com/barcode.ashx?data=' + data['barcode'] + '&code=EAN13&dpi=96&dataseparator=');
                    $('#printed-div table p').html('<b>PN:</b> ' + data['part_number'] + ' <b>O:</b> ' + data['order_number'] + ' <b>Laser:</b> ' + data['laser_marking'] + ' <b>Material:</b> ' + data['material'] + ' <b>Color:</b> ' + data['color'] + ' <b>Q:</b> ' + data['quantity'] + ' <b>DM:</b> ' + data['demension']);
                    $('#printed-div').slideToggle(function() {
                        
                        window.print();
                        
                    });
                    
                }
                  
            }

        });*/
        
    });
    
}

function scan_barcode() {
    
    $('#barcode').change(function() {
        
        //var barcode = $(this).val();
        
        if ($(this).val() != '') {
        
        var scaned = {
            
            barcode: $('#barcode').val()
            
        }
        
        var result = JSON.stringify(scaned);
        
        $.ajax({

            url: '/admin/scan_barcode', 
            type: 'POST', 
            cache: false,
            data: {'json_data': result},
            dataType: 'json',
            beforeSend: function() {

                $('#part_number').html('Loading...');

            },
            success: function (data) {
                
                /*$('#part_number').val('').prop('disabled', false);
                $('#material').val('').prop('disabled', false);
                $('#demension').val('').prop('disabled', false);*/
                
                if (data['part_number'] != '') {
                    
                    $('#part_number').val(data['part_number']).css({'background-color': 'rgba(0, 128, 0, 0.1)'});
                    
                }
                
                if (data['material'] != '') {
                    
                    $('#material').val(data['material']).css({'background-color': 'rgba(0, 128, 0, 0.1)'});
                    
                }
                
                if (data['dm2'] != '') {
                    
                    $('#demension').val(data['dm2']).css({'background-color': 'rgba(0, 128, 0, 0.1)'});
                    
                }
                  
            }

        });
            
        }
        
    });
    
    $('#part_number').change(function() {
        
        //var barcode = $(this).val();
        
        if ($(this).val() != '') {
        
        var scaned = {
            
            part_number: $('#part_number').val()
            
        }
        
        var result = JSON.stringify(scaned);
        
        $.ajax({

            url: '/admin/scan_barcode', 
            type: 'POST', 
            cache: false,
            data: {'json_data': result},
            dataType: 'json',
            beforeSend: function() {

                $('#part_number').html('Loading...');

            },
            success: function (data) {
                
                /*$('#part_number').val('').prop('disabled', false);
                $('#material').val('').prop('disabled', false);
                $('#demension').val('').prop('disabled', false);*/
                
                
                //if (data['barcode'] != '') {
                    
                    $('#barcode').val(data['barcode']).css({'background-color': 'rgba(0, 128, 0, 0.1)'});
                    
                //}
                
                if (data['material'] != '') {
                    
                    $('#material').val(data['material']).css({'background-color': 'rgba(0, 128, 0, 0.1)'});
                    
                }
                
                if (data['dm2'] != '') {
                    
                    $('#demension').val(data['dm2']).css({'background-color': 'rgba(0, 128, 0, 0.1)'});
                    
                }
                  
            }

        });
        
    }
        
    });
    
}

function edit_package() {
    
    $('#edit-package').click(function() {
        
        var new_package = {
            
            package_id: $('#package_id').val(),
            package_number: $('#package_number').val(),
            date: $('#date').val(),
            time: $('#time').val()
            
        }
        
        var result = JSON.stringify(new_package);
        
        $.ajax({

            url: '/admin/redaction_package', 
            type: 'POST', 
            cache: false,
            data: {'json_data': result},
            dataType: 'json',
            success: function (data) {

                if (data == true) {
                    
                    alert('Package was edited.');
                    
                } else {
                    
                    alert('Package was not edited.');
                    
                }

            }

        });
        
    });
    
}

function edit_order() {
    
    $('#edit-order').click(function() {
        
        var new_order = {
            
            order_id: $('#order_id').val(),
            barcode: $('#barcode').val(),
            order_number: $('#order_number').val(),
            part_number: $('#part_number').val(),
            laser_marking: $('#laser_marking').val(),
            material: $('#material').val(),
            color: $('#color').val(),
            quantity: $('#quantity').val(),
            demension: $('#demension').val(),
            note: $('#note').val(),
            
        }
        
        var result = JSON.stringify(new_order);
        
        $.ajax({

            url: '/admin/redaction_order', 
            type: 'POST', 
            cache: false,
            data: {'json_data': result},
            dataType: 'json',
            success: function (data) {

                if (data == true) {
                    
                    alert('Package was edited.');
                    
                } else {
                    
                    alert('Package was not edited.');
                    
                }

            }

        });
        
    });
    
}

function add_new_order() {
    
    $('#create-order').click(function() {
        
        if ($('#part_number').val() == '') {
            
            alert('Part number is not filled.');
            
        } else {
        
        var last = location.pathname.split('/');
        
        var new_order = {
            
            package_id: last[3],
            order_number: $('#order_number').val(),
            barcode: $('#barcode').val(),
            part_number: $('#part_number').val(),
            laser_marking: $('#laser_marking').val(),
            material: $('#material').val(),
            color: $('#color').val(),
            quantity: $('#quantity').val(),
            demension: $('#demension').val(),
            note: $('#note').val(),
            
        }
        
        var result = JSON.stringify(new_order);
            
        $.ajax({

            url: '/admin/new_order', 
            type: 'POST', 
            cache: false,
            data: {'json_data': result},
            dataType: 'json',
            success: function (data) {

                if (data == true) {
                    
                    alert('Order was created.');
                    
                    location.href = location.pathname;
                    
                } else {
                    
                    alert('Order was not created.');
                    
                }

            }

        });
            
        }
        
    });
    
}

function add_new_package() {
    
    $('#create-package').click(function() {
        
        var new_package = {
            
            tool: $('#tool').val(),
            package_number: $('#package_number').val()
            
        }
        
        var result = JSON.stringify(new_package);
        
        if (new_package.tool == '') {
        
            alert('You need to specify the department');
            
        } else {
            $.ajax({

                url: '/admin/new_package', 
                type: 'POST', 
                cache: false,
                data: {'json_data': result},
                dataType: 'json',
                success: function (data) {

                    if (data['status'] == true) {

                        alert('New package was created.');

                        location.href = "/admin/orders/" + data['max'];

                    } else {

                        alert('New package was not created.');

                    }

                }

            });
        }
        
    });
    
}

function dinamic_content_height() {
    
    var height = $(window).height() - $('#top').height();
    
    $('#content-box').height(height);
    
    $(window).resize(dinamic_content_height);
    
}