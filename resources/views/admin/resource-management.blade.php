{{-- Extends Layout Auth --}}
@extends('layouts.app')
@section('title', 'Resources Management')

{{-- Push ke Stack --}}
@push('extra-lib-css')
{{-- DataTables --}}
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedheader/css/fixedHeader.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-select/css/select.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/jstree/themes/default/style.min.css') }}">
@endpush
@push('extra-lib-js')
{{-- DataTables  & Plugins --}}
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-fixedcolumns/js/fixedColumns.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-fixedheader/js/fixedHeader.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-select/js/select.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('plugins/jstree/jstree.min.js') }}"></script>
@endpush
@push('extra-css')
<style type="text/css">
    div.dataTables_wrapper div.dataTables_processing {
        position: absolute;
        top: 15% !important;
    }
</style>
@endpush
@push('extra-js')
{{-- Script untuk Properties/dll --}}
    <script>
        {{-- Setup Ajax Header --}}
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        {{-- inisiasi tabel user --}}
        var RefreshUserTable = $("#table-user").DataTable({
            pageLength: 5,
            lengthMenu: [
              [5],
              [5]
            ],
            keys: !0,
            stateSave: !0,
            fixedHeader: !0,
            processing: !0,
            language: {
                paginate: {
                  previous: "<i class='fa fa-chevron-left'>",
                  next: "<i class='fa fa-chevron-right'>"
                }
            },
            drawCallback: function() {
              $(".dataTables_paginate > .pagination").addClass("pagination-rounded"), $('[data-bs-toggle="tooltip"]').tooltip();

            },
            serverSide: true,
            ajax: {
                url: '{{ url('Request/Admin/List-user') }}',
                data: function(d) {
                  return $.extend({}, d, {
                    /* parameter tambahan */
                  });
                },
            },
            columns: [{
                    data: "name",
                    name: "name",
                    "defaultContent": "-"
                },
                {
                    data: "email",
                    name: "email",
                    "defaultContent": "-"
                },
                {
                    data: "is_active",
                    name: "is_active"
                },
                {
                    data: "tools",
                    name: "tools"
                },
            ]
        });

        // inisasi tabel role
        var RefreshRoleTable = $("#table-role").DataTable({
            pageLength: 5,
            lengthMenu: [
                [5],
                [5]
            ],
            keys: !0,
            stateSave: !0,
            fixedHeader: !0,
            processing: !0,
            language: {
                paginate: {
                    previous: "<i class='fa fa-chevron-left'>",
                    next: "<i class='fa fa-chevron-right'>"
                }
            },
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded"),
                    $('[data-bs-toggle="tooltip"]').tooltip();
            },
            serverSide: true,
            ajax: {
                url: '{{ url('Request/Admin/List-role') }}',
                data: function(d) {
                    return $.extend({}, d, {
                        /* parameter tambahan */
                    });
                },
            },
            columns: [{
                    data: "role",
                    name: "role",
                    "defaultContent": "-"
                },
                {
                    data: "desc",
                    name: "desc",
                    "defaultContent": "-"
                },
                {
                    data: "is_active",
                    name: "is_active"
                },
                {
                    data: "tools",
                    name: "tools"
                },
            ]
        });

        // inisiasi tabel user has roles
        var table_mapping_roles = $("#table-user-has-roles").DataTable({
            pageLength: 5,
            lengthMenu: [
                [5],
                [5]
            ],
            // dom: 'Bftip',
            // buttons: [
            //   {
            //     text: '<i class="fas fa-retweet"></i>'
            //     , className: 'btn btn-light"'
            //     , action: function (e, dt, node, config) {
            //       dt.ajax.reload();
            //     }
            //   },
            // ],
            keys: !0,
            stateSave: !0,
            fixedHeader: !0,
            processing: !0,
            language: {
                paginate: {
                    previous: "<i class='fa fa-chevron-left'>",
                    next: "<i class='fa fa-chevron-right'>"
                }
            },
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded"),
                    $('[data-bs-toggle="tooltip"]').tooltip();
            },
            serverSide: true,
            ajax: {
                url: '{{ url('Request/Admin/List-user-roles') }}',
                data: function(d) {
                    return $.extend({}, d, {
                        /* parameter tambahan */
                        'role_id': $('#modal-role-mapping input[type=hidden][name=role_id]').val(),
                    });
                },
            },
            columns: [{
                    data: "name",
                    name: "name",
                    "defaultContent": "-"
                },
                {
                    data: "tools",
                    name: "tools"
                },
            ]
        });

        // Inisiasi Treeview untuk Menu
        $("#role-menu-treeview").jstree({
            core: {
                themes: {
                    responsive: !1
                },
                check_callback: !0,
                data: {
                    url: '{{ url('Request/Admin/List-accessable-menu') }}',
                    type: 'GET',
                    dataType: 'JSON',
                    contentType: 'application/json',
                    data: function() {
                        return {
                            'role_id': $('#modal-role-mapping input[type=hidden][name=role_id]').val()
                        };
                    }
                }
            },
            types: {
                default: {
                    icon: "dripicons-menu text-primary"
                },
                file: {
                    icon: "dripicons-document  text-primary"
                }
            },
            plugins: ["state", "types", "checkbox"]
        }).on('ready.jstree', function(d) {
            // d itu event dan punya id dari event yg di lempar
            $.jstree.reference('#' + d.target.id).uncheck_all();
            var data = $.jstree.reference('#' + d.target.id).get_json();
            fCheckMarkMenu(d.target.id, data)
        }).on('refresh.jstree', function(d) {
            // d itu event dan punya id dari event yg di lempar
            $.jstree.reference('#' + d.target.id).uncheck_all();
            var data = $.jstree.reference('#' + d.target.id).get_json();
            fCheckMarkMenu(d.target.id, data)
        });

        // Menu CRUD Treeview
        $("#menu-treeview").jstree({
            core: {
                themes: {
                    responsive: !1
                },
                check_callback: !0,
                data: {
                    url: '{{ url('Request/Admin/List-menu') }}',
                    type: 'GET',
                    dataType: 'JSON',
                    contentType: 'application/json',
                    data: function(node) {
                        // return { 'id' : node.id };
                    }
                }
            },
            types: {
                default: {
                    icon: "dripicons-menu text-primary"
                },
            },
            contextmenu: {
                'items': function(node) {
                    var option = {
                        "edit": {
                            label: 'Edit',
                            title: 'Edit Menu/Request',
                            action: function(data) {
                                var inst = $.jstree.reference(data.reference),
                                    obj = inst.get_node(data.reference);
                                editMenu(obj.id)
                            }
                        },
                        /*next item*/
                    };

                    return option;
                }
            },
            plugins: ["contextmenu", "state", "types"]
        });
    </script>
    {{-- Script untuk Func/Dom manupilation lainnya --}}
    <script>
        // On Submit Form
        $('#form-user').on("submit", function(e) {
            e.preventDefault();
            toggleStateButton($('button[type=submit][form=form-user]'));
            var dataForm = $(this).serializeArray();
            $.ajax({
                type: "post",
                url: '{{ url('Request/Admin/Save-user') }}',
                data: dataForm,
            }).done(function(data, textStatus, jqXHR) {
              window['toast'].fire({
                icon: 'success',
                title: 'Success submit form user.'
              });
            }).fail(function(jqXHR, textStatus, errorThrown) {
                ajaxFailedNotify(jqXHR.responseJSON, errorThrown)
            }).always(function(data, textStatus, jqXHR) {
                // console.log(data);
                toggleStateButton($('button[type=submit][form=form-user]'), false);
            });
        });

        $('#form-role').on("submit", function(e) {
            e.preventDefault();
            toggleStateButton($('button[type=submit][form=form-role]'));
            var dataForm = $(this).serializeArray();
            $.ajax({
                type: "post",
                url: '{{ url('Request/Admin/Save-role') }}',
                data: dataForm,
            }).done(function(data, textStatus, jqXHR) {
              window['toast'].fire({
                icon: 'success',
                title: 'Success submit form role.'
              });
            }).fail(function(jqXHR, textStatus, errorThrown) {
                ajaxFailedNotify(jqXHR.responseJSON, errorThrown)
            }).always(function(data, textStatus, jqXHR) {
                // console.log(data);
                toggleStateButton($('button[type=submit][form=form-role]'), false);
            });
        });

        $('#form-menu').on("submit", function(e) {
            e.preventDefault();
            toggleStateButton($('button[type=submit][form=form-menu]'));
            var dataForm = $(this).serializeArray();
            $.ajax({
                type: "post",
                url: '{{ url('Request/Admin/Save-menu') }}',
                data: dataForm,
            }).done(function(data, textStatus, jqXHR) {
              window['toast'].fire({
                icon: 'success',
                title: 'Success submit form menu.'
              });
            }).fail(function(jqXHR, textStatus, errorThrown) {
                ajaxFailedNotify(jqXHR.responseJSON, errorThrown)
            }).always(function(data, textStatus, jqXHR) {
                // console.log(data);
                toggleStateButton($('button[type=submit][form=form-menu]'), false);
            });
        });

        // Modal Close Listener untuk reset semua input/text dan lainnya
        $('#modal-user-form').on('hidden.bs.modal', function() {
            // reset input
            $('form', this).trigger('reset');
            // cari ada input hidden dg nama id ga di form nya
            $('input[type=hidden][name=id]', this).remove();
        });
        $('#modal-role-form').on('hidden.bs.modal', function() {
            // reset input
            $('form', this).trigger('reset');
            // cari ada input hidden dg nama id ga di form nya
            $('input[type=hidden][name=id]', this).remove();
        });
        $('#modal-menu-form').on('hidden.bs.modal', function() {
            // reset input
            $('form', this).trigger('reset');
            // cari ada input hidden dg nama id ga di form nya
            $('input[type=hidden][name=id]', this).remove();
        });

        // Modal open listener
        $('#modal-menu-form').on('show.bs.modal', function() {
            fGetListParentMenu();
        });

        // func generate password
        function generatePassword() {
            $('#form-user input[name=password]').val('alpha2022');
        }

        // toggling modal show/hide
        function toggleModal(idModal) {
            $('#' + idModal).modal('toggle');
        }

        // edit user
        function editUser(idUser) {
            // dapatin informasi user dan masukin ke form nya
            // console.log(idUser);
            $.ajax({
                type: "get",
                url: '{{ url('Request/Admin/Info-user') }}',
                data: {
                    'idUser': idUser
                },
            }).done(function(data, textStatus, jqXHR) {
                // fill input dg data
                $('#form-user input[name=name]').val(data['name']);
                $('#form-user input[name=email]').val(data['email']);
                // tambahin id
                var inputHidden = $('<input>').attr({
                    type: 'hidden',
                    name: 'id',
                    value: data['id']
                });
                $('#form-user').append(inputHidden);
                // toggle modal nya
                toggleModal('modal-user-form');
            }).fail(function(jqXHR, textStatus, errorThrown) {
                ajaxFailedNotify(jqXHR.responseJSON, errorThrown)
            }).always(function(data, textStatus, jqXHR) {
                // console.log(data);
            });
        }

        // edit role
        function editRole(idRole) {
            // dapatin informasi role dan masukin ke form nya
            $.ajax({
                type: "get",
                url: '{{ url('Request/Admin/Info-role') }}',
                data: {
                    'idRole': idRole
                },
            }).done(function(data, textStatus, jqXHR) {
                // fill input dg data
                $('#form-role input[name=role]').val(data['role']);
                $('#form-role textarea[name=desc]').val(data['desc']);
                // tambahin id
                var inputHidden = $('<input>').attr({
                    type: 'hidden',
                    name: 'id',
                    value: data['id']
                });
                $('#form-role').append(inputHidden);
                // toggle modal nya
                toggleModal('modal-role-form');
            }).fail(function(jqXHR, textStatus, errorThrown) {
                ajaxFailedNotify(jqXHR.responseJSON, errorThrown)
            }).always(function(data, textStatus, jqXHR) {
                // console.log(data);
            });
        }

        // edit menu
        function editMenu(idMenu) {
            // toggle modal nya
            toggleModal('modal-menu-form');
            // dapatin informasi menu dan masukin ke form nya
            $.ajax({
                type: "get",
                url: '{{ url('Request/Admin/Info-menu') }}',
                data: {
                    'idMenu': idMenu
                },
            }).done(function(data, textStatus, jqXHR) {
                // fill input dg data
                $('#form-menu select[name=type]').val(data['type']).change();
                $('#form-menu select[name=parent]').val(data['parent']).change();
                $('#form-menu input[name=order]').val(data['order']);
                $('#form-menu input[name=icon]').val(data['icon']);
                $('#form-menu input[name=name]').val(data['name']);
                $('#form-menu input[name=link]').val(data['link']);
                $('#form-menu select[name=child]').val(data['child']).change();

                // tambahin id
                var inputHidden = $('<input>').attr({
                    type: 'hidden',
                    name: 'id',
                    value: data['id']
                });
                $('#form-menu').append(inputHidden);
            }).fail(function(jqXHR, textStatus, errorThrown) {
                ajaxFailedNotify(jqXHR.responseJSON, errorThrown)
            }).always(function(data, textStatus, jqXHR) {
                // console.log(data);
            });
        }

        // Func untuk assign/remove user ke/dari role
        function mapUserRole(idUser, state) {
            var d = {
                'role_id': $('#modal-role-mapping input[type=hidden][name=role_id]').val(),
                'idUser': idUser,
                'state': (state ? 'assign' : 'false')
            };
            formSend('{{ url('Request/Admin/Map-user-role') }}', d, 'post');
            table_mapping_roles.ajax.reload();
        }

        // mapping role dg access dan user
        function mappingRole(idRole, roleName) {
            // dapatin informasi list user yg di role itu, list user yang ga di role itu, dan page/reqeust yg dimiliki role itu
            $('#modal-role-mapping input[type=hidden][name=role_id]').val(idRole);
            $('#role-label').html(roleName);
            table_mapping_roles.ajax.reload();
            $("#role-menu-treeview").jstree('refresh');
            // toggle modal nya
            toggleModal('modal-role-mapping');
        }

        // ambil list menu/request yg bisa jadi parent
        function fGetListParentMenu() {
            $.ajax({
                type: "get",
                url: '{{ url('Request/Admin/List-parent-menus') }}',
                data: {
                    {{-- kosong --}}
                },
            }).done(function(data, textStatus, jqXHR) {
                var l = '<option value="0">No parent</option>';
                for (var i = 0; i < data.length; i++) {
                    l += '<option value="' + data[i]['id'] + '">' + data[i]['name'] + '</option>';
                }
                $('#form-menu select[name=parent]').html(l);
                // $('#form-menu select[name=parent]').change();
            }).fail(function(jqXHR, textStatus, errorThrown) {
                ajaxFailedNotify(jqXHR.responseJSON, errorThrown)
            }).always(function(data, textStatus, jqXHR) {
                // console.log(data);
            });
        }

        // func untuk save role access ke menu/request
        function saveRoleAccess(idJstree) {
            var role_id = $('#modal-role-mapping input[type=hidden][name=role_id]').val(),
                all_nodes = $.jstree.reference('#' + idJstree).get_json('#', {
                    flat: true
                }).map(function(a) {
                    return a.id;
                }),
                checked = $.jstree.reference('#' + idJstree).get_checked().concat($.jstree.reference('#' + idJstree)
                    .get_undetermined()),
                unchecked = all_nodes.filter(x => !checked.includes(x)).concat(checked.filter(x => !all_nodes.includes(x)));

            $.ajax({
                type: "post",
                url: '{{ url('Request/Admin/Save-access-list-roles') }}',
                data: {
                    'role_id': role_id,
                    'checked': checked,
                    'unchecked': unchecked,
                },
            }).done(function(data, textStatus, jqXHR) {
              window['toast'].fire({
                icon: 'success',
                title: 'Success submit form user.'
              });
            }).fail(function(jqXHR, textStatus, errorThrown) {
                ajaxFailedNotify(jqXHR.responseJSON, errorThrown)
            }).always(function(data, textStatus, jqXHR) {
                // refresh jstree nya dan table mapping nya
                $('#' + idJstree).jstree('refresh');
                table_mapping_roles.ajax.reload();
            });

        }

        // untuk checkmark dan uncheckmark treeview
        function fCheckMarkMenu(idJstree, data) {
            for (var i = 0; i < data.length; i++) {
                if (data[i].data.checked) {
                    $.jstree.reference('#' + idJstree).check_node($.jstree.reference('#' + idJstree).get_node(data[i].id));
                } else {
                    $.jstree.reference('#' + idJstree).uncheck_node($.jstree.reference('#' + idJstree).get_node(data[i]
                        .id));
                }
                if (data[i].children.length > 0) {
                    fCheckMarkMenu(idJstree, data[i].children);
                }
            }
            return;
        }
    </script>
@endpush

@section('content')
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Resources Management</h1>
          </div>
          <div class="col-sm-6">
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        {{-- BEGIN Konten User dan Role --}}
        <div class="row g-3">
            <div class="col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="header-title">User</h4>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm"
                                    onclick="RefreshUserTable.ajax.reload()">
                                    Refresh <i class="fas fa-retweet "></i>
                                </button>
                                <button type="button" class="btn btn-success btn-sm"
                                    onclick="toggleModal('modal-user-form')">
                                    Add <i class="fas fa-plus "></i>
                                </button>
                            </div>
                        </div>
                        <div class="" style="min-height: 400px">
                            <table id="table-user" class="table table-sm table-striped dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Active</th>
                                        <th>Tools</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- ehe --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-7 col-md-12">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="header-title">Role</h4>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm"
                                    onclick="RefreshRoleTable.ajax.reload()">
                                    Refresh <i class="fas fa-retweet "></i>
                                </button>
                                <button type="button" class="btn btn-success btn-sm"
                                    onclick="toggleModal('modal-role-form')">
                                    Add <i class="fas fa-plus "></i>
                                </button>
                            </div>
                        </div>
                        <div class="" style="min-height: 400px">
                            <table id="table-role" class="table table-sm table-striped dt-responsive w-100">
                                <thead>
                                    <tr>
                                        <th>Role</th>
                                        <th>Desc</th>
                                        <th>Active</th>
                                        <th>Tools</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- ehe --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-md-12">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="header-title">Menu & Request</h4>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm"
                                    onclick="$('#menu-treeview').jstree('refresh')">
                                    Refresh <i class="fas fa-retweet "></i>
                                </button>
                                <button type="button" class="btn btn-success btn-sm"
                                    onclick="toggleModal('modal-menu-form')">
                                    Add <i class="fas fa-plus "></i>
                                </button>
                            </div>
                        </div>
                        <div data-simplebar style="max-height: 400px;">
                            <div id="menu-treeview">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </section>

    {{-- BEGIN Modal --}}
    <div id="modal-role-mapping" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalRole"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalRole">Role Mapping [<span id="role-label"></span>]</h4>
                    <button type="button" class="btn fas fa-window-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body h-100">
                    {{-- Input hidden untuk menentukan default role nya apa --}}
                    <input type="hidden" name="role_id" value="1">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="card-header">List User</h4>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm"
                                        onclick="table_mapping_roles.ajax.reload()">
                                        Refresh <i class="fas fa-retweet "></i>
                                    </button>
                                </div>
                            </div>
                            <table id="table-user-has-roles" class="table table-sm table-striped dt-responsive w-100">
                                <thead>
                                    <tr>
                                        <th class="w-75">Name</th>
                                        <th>Tools</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- ehe --}}
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-5">
                            <h4 class="card-header mb-3">List Menu/Request</h4>
                            <div data-simplebar style="max-height: 400px;">
                                <div id="role-menu-treeview">
                                    {{-- treeview disini --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick="saveRoleAccess('role-menu-treeview')">Save
                        Role Access
                        List</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-user-form" tabindex="-1" role="dialog" aria-labelledby="modalUserForm"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalUserForm">Modal User</h4>
                    <button type="button" class="btn fas fa-window-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-user">
                        <div class="mb-3">
                            <label for="inputName" class="form-label">Name</label>
                            <input type="text" id="inputName" class="form-control" placeholder="Name" name="name"
                                required="true" autocomplete="off">
                        </div>

                        <div class="mb-3">
                            <label for="inputEmail" class="form-label">Email</label>
                            <input type="email" id="inputEmail" name="email" class="form-control"
                                placeholder="Email" required="true" autocomplete="off">
                        </div>

                        <div class="mb-3">
                            <label for="inputPassword" class="form-label">Show/Hide Password</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="inputPassword" class="form-control"
                                    placeholder="Enter your password" name="password" required="true"
                                    autocomplete="off">
                                <div class="input-group-text" data-password="false">
                                    <span class="fa fa-lock"></span>
                                </div>
                            </div>
                            {{-- <span class="help-block"><small>Or use default password by <a onclick="generatePassword()"><code style="cursor:pointer">click here</code></a>.</small></span> --}}
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <button type="reset" class="btn btn-secondary" form="form-user">Reset</button>
                    <button type="submit" class="btn btn-success" form="form-user">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-role-form" tabindex="-1" role="dialog" aria-labelledby="modalRoleForm"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalRoleForm">Modal Role</h4>
                    <button type="button" class="btn fas fa-window-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-role">
                        <div class="mb-3">
                            <label for="inputRole" class="form-label">Role</label>
                            <input type="text" id="inputRole" class="form-control" placeholder="role" name="role"
                                required="true" autocomplete="off">
                        </div>

                        <div class="mb-3">
                            <label for="inputDesc" class="form-label">Description</label>
                            <textarea class="form-control" id="inputDesc" rows="2" name="desc" autocomplete="off" required="true"></textarea>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <button type="reset" class="btn btn-secondary" form="form-role">Reset</button>
                    <button type="submit" class="btn btn-success" form="form-role">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-menu-form" tabindex="-1" role="dialog" aria-labelledby="modalRoleForm"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalRoleForm">Modal Menu</h4>
                    <button type="button" class="btn fas fa-window-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-menu">

                        <div class="mb-3 form-group">
                            <label for="inputType" class="form-label">Type</label>
                            <select class="form-control" id="inputType" name="type" required="true">
                                <option value="page">Page</option>
                                <option value="request">Request</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="inputParent" class="form-label">Parent Menu</label>
                            <select class="form-control" id="inputParent" name="parent" required="true">
                                <option value=""></option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="inputOrder" class="form-label">Menu Order</label>
                            <input type="text" id="inputOrder" class="form-control" placeholder="1 or 2 etc"
                                name="order" required="true" autocomplete="off" value="1">
                        </div>

                        <div class="mb-3">
                            <label for="inputIcon" class="form-label">Menu Icon</label>
                            <input type="text" id="inputIcon" class="form-control" placeholder="fa-plus-circle" name="icon" required="true" autocomplete="off" value="fas fa-tag">
                        </div>

                        <div class="mb-3">
                            <label for="inputMenuName" class="form-label">Menu Name</label>
                            <input type="text" id="inputMenuName" class="form-control" placeholder="menu name"
                                name="name" required="true" autocomplete="off">
                        </div>

                        <div class="mb-3">
                            <label for="inputMenuLink" class="form-label">Menu Link</label>
                            <input type="text" id="inputMenuLink" class="form-control" placeholder="menu link"
                                name="link" required="true" autocomplete="off" value="#">
                        </div>

                        <div class="mb-3">
                            <label for="inputChildMenu" class="form-label">Child Menu?</label>
                            <select class="form-control" id="inputChildMenu" name="child" required="true">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                            <span class="help-block"><small>If it's yes, it's mean menu/request will show on select parent menu.</small></span>
                        </div>

                        <div class="mb-3">
                            <label for="inputActive" class="form-label">Is Active?</label>
                            <select class="form-control" id="inputActive" name="active" required="true">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                            <span class="help-block"><small>Meaning is menu/request accessable.</small></span>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <button type="reset" class="btn btn-secondary" form="form-menu">Reset</button>
                    <button type="submit" class="btn btn-success" form="form-menu">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END Modal --}}
@stop