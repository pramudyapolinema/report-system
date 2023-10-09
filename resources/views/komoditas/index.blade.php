@extends('adminlte::page')
@section('title', 'Komoditas')
@section('content_header')
    <h1 class="m-0 text-dark">Komoditas</h1>
@endsection
@section('plugins.Datatables', true)

@php
    $heads = [['label' => 'No', 'width' => 2], 'Nama', ['label' => 'Actions', 'width' => 10]];
    $config = [
        'serverSide' => true,
        'processing' => true,
        'ajax' => ['url' => route('komoditas.index')],
        'columns' => [['data' => 'DT_RowIndex', 'orderable' => 'false', 'searchable' => 'false'], ['data' => 'name'],  ['data' => 'actions']],
    ];
@endphp
@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="ml-auto">
                <x-adminlte-button label="Komoditas" theme="primary" icon="fas fa-plus" data-toggle="modal" data-target="#addKomoditasModal" />
            </div>
        </div>
        <div class="card-body">
            <x-adminlte-datatable id="table-komoditas" :heads="$heads" :config="$config" striped hoverable>
            </x-adminlte-datatable>
        </div>
    </div>
    <form id="addKomoditas">
        <x-adminlte-modal id="addKomoditasModal" title="Add Komoditas">
            @csrf
            <x-adminlte-input name="name" label="Nama Komoditas" placeholder="Masukkan Nama Komoditas" disable-feedback />
            <x-slot name="footerSlot">
                <x-adminlte-button theme="primary" label="Simpan" type="submit" id="submitButton" />
                <x-adminlte-button theme="default" label="Batalkan" data-dismiss="modal" id="dismissButton" />
            </x-slot>
        </x-adminlte-modal>
    </form>
    <form id="editKomoditas">
        <x-adminlte-modal id="editKomoditasModal" title="Edit Komoditas">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="editId">
            <x-adminlte-input id="editName" name="name" label="Nama Komoditas" placeholder="Masukkan Nama Komoditas" disable-feedback />
            <x-slot name="footerSlot">
                <x-adminlte-button theme="primary" label="Simpan" type="submit" id="submitEditButton" />
                <x-adminlte-button theme="default" label="Batalkan" data-dismiss="modal" id="dismissEditButton" />
            </x-slot>
        </x-adminlte-modal>
    </form>
@endsection

@section('js')
    <script>
        $('#addKomoditas').on('submit', function(e) {
            e.preventDefault();
            $('#submitButton').attr('disabled', true);
            $.ajax({
                url: "{{ route('komoditas.store') }}",
                type: "POST",
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                data: new FormData(this),
                error: function(data) {
                    toastr.error(data.responseJSON.message, 'Error');
                },
                success: function(data) {
                    toastr.success(data.message, 'Sukses');
                    $('#addKomoditasModal').modal('toggle');
                    $('#table-komoditas').DataTable().ajax.reload();
                }
            });
            $('#submitButton').removeAttr('disabled');
            return false;
        });

        $(document).on("click", "#deleteButton", function(e) {
            e.preventDefault();
            Swal.fire({
                customClass: {
                    confirmButton: 'bg-danger',
                },
                title: 'Apakah anda yakin?',
                text: "Apakah anda yakin ingin menghapus data ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    e.preventDefault();
                    var id = $(this).data("id");
                    var route = "{{ route('komoditas.destroy', ':id') }}";
                    route = route.replace(':id', id);
                    $.ajax({
                        url: route,
                        type: 'DELETE',
                        data: {
                            _token: $("meta[name='csrf-token']").attr("content"),
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Success',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK',
                                timer: 1000,
                                timerProgressBar: true,
                            })
                            $('#table-komoditas').DataTable().ajax.reload();
                        },
                        error: function(data) {
                            toastr.error(data.responseJSON.message, 'Error');
                        }
                    });
                }
            });
        });

        $(document).on("click", "#editButton", function(e) {
            var id = $(this).data("id");
            var route = "{{ route('komoditas.edit', ':id') }}";
            route = route.replace(':id', id);
            $.ajax({
                url: route,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#editId').val(data.id);
                    $('#editName').val(data.name);
                }
            });
        });

        $('#editKomoditas').on('submit', function(e) {
            e.preventDefault();
            $('#submitEditButton').attr('disabled', true);
            var id = $('#editId').val();
            var route = "{{ route('komoditas.update', ':id') }}";
            route = route.replace(':id', id);
            $.ajax({
                url: route,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                data: new FormData(this),
                error: function(data) {
                    toastr.error(data.responseJSON.message, 'Error');
                },
                success: function(data) {
                    toastr.success(data.message, 'Sukses');
                    $('#editKomoditasModal').modal('toggle');
                    $('#table-komoditas').DataTable().ajax.reload();
                }
            });
            $('#submitEditButton').removeAttr('disabled');
            return false;
        });
    </script>
@endsection
