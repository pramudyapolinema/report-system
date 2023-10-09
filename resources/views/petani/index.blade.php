@extends('adminlte::page')
@section('title', 'Petani')
@section('content_header')
    <h1 class="m-0 text-dark">Petani</h1>
@endsection
@section('plugins.Datatables', true)

@php
    $heads = [['label' => 'No', 'width' => 2], 'Nama', 'NIK', 'Kelompok Tani', 'Alamat', 'Luas Lahan', ['label' => 'Actions', 'width' => 10]];
    $config = [
        'serverSide' => true,
        'processing' => true,
        'ajax' => ['url' => route('petani.index')],
        'columns' => [['data' => 'DT_RowIndex', 'orderable' => 'false', 'searchable' => 'false'], ['data' => 'name'], ['data' => 'nik'], ['data' => 'kelompok_tani'], ['data' => 'address'], ['data' => 'luas_lahan'], ['data' => 'actions']],
    ];
@endphp
@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="ml-auto">
                <x-adminlte-button label="Petani" theme="primary" icon="fas fa-plus" data-toggle="modal" data-target="#addPetaniModal" />
            </div>
        </div>
        <div class="card-body">
            <x-adminlte-datatable id="table-petani" :heads="$heads" :config="$config" striped hoverable>
            </x-adminlte-datatable>
        </div>
    </div>
    <form id="addPetani">
        <x-adminlte-modal id="addPetaniModal" title="Add Petani">
            @csrf
            <x-adminlte-input name="name" label="Nama Lengkap" placeholder="Masukkan Nama Lengkap" disable-feedback />
            <x-adminlte-input name="nik" label="NIK" placeholder="Masukkan NIK" disable-feedback />
            <div class="form-group">
                <label for="kelompok_tani" class="form-label">Kelompok Tani</label>
                <div class="input-group">
                    <select id="kelompok_tani_id" name="kelompok_tani_id" class="form-control">
                    </select>
                </div>
            </div>
            <x-adminlte-textarea name="address" label="Alamat" placeholder="Masukkan Alamat" disable-feedback />
            <x-adminlte-input name="luas_lahan" label="Luas Lahan" placeholder="Masukkan Luas Lahan" disable-feedback />
            <x-slot name="footerSlot">
                <x-adminlte-button theme="primary" label="Simpan" type="submit" id="submitButton" />
                <x-adminlte-button theme="default" label="Batalkan" data-dismiss="modal" id="dismissButton" />
            </x-slot>
        </x-adminlte-modal>
    </form>
    <form id="editPetani">
        <x-adminlte-modal id="editPetaniModal" title="Edit Petani">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="editId">
            <x-adminlte-input id="editName" name="name" label="Nama Lengkap" placeholder="Masukkan Nama Lengkap" disable-feedback />
            <x-adminlte-input id="editNik" name="nik" label="NIK" placeholder="Masukkan NIK" disable-feedback />
            <div class="form-group">
                <label for="editKelompokTani" class="form-label">Kelompok Tani</label>
                <div class="input-group">
                    <select id="editKelompokTani" name="kelompok_tani_id" class="form-control">
                    </select>
                </div>
            </div>
            <x-adminlte-textarea name="editAddress" label="Alamat" placeholder="Masukkan Alamat" disable-feedback />
            <x-adminlte-input name="editLuas_lahan" label="Luas Lahan" placeholder="Masukkan Luas Lahan" disable-feedback />
            <x-slot name="footerSlot">
                <x-adminlte-button theme="primary" label="Simpan" type="submit" id="submitEditButton" />
                <x-adminlte-button theme="default" label="Batalkan" data-dismiss="modal" id="dismissEditButton" />
            </x-slot>
        </x-adminlte-modal>
    </form>
@endsection

@section('js')
    <script>
        $('#kelompok_tani_id').select2({
            ajax: {
                url: "{{ route('dropdown.kelompok-tani') }}",
                data: function(params) {
                    return {
                        search: params.term,
                        type: 'public',
                    }
                },
            },
            placeholder: "Pilih Kelompok Tani",
            width: '100%',
            theme: 'bootstrap4',
            dependantDropdown: $('#addKelompokTani'),
        });
        $('#addPetani').on('submit', function(e) {
            e.preventDefault();
            $('#submitButton').attr('disabled', true);
            $.ajax({
                url: "{{ route('petani.store') }}",
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
                    $('#addPetaniModal').modal('toggle');
                    $('#table-petani').DataTable().ajax.reload();
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
                    var route = "{{ route('petani.destroy', ':id') }}";
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
                            $('#table-petani').DataTable().ajax.reload();
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
            var route = "{{ route('petani.edit', ':id') }}";
            route = route.replace(':id', id);
            $('#editKelompokTani').select2({
                ajax: {
                    url: "{{ route('dropdown.kelompok-tani') }}",
                    data: function(params) {
                        return {
                            search: params.term,
                            type: 'public',
                        }
                    },
                },
                placeholder: "Pilih Kelompok Tani",
                width: '100%',
                theme: 'bootstrap4',
                dependantDropdown: $('#editKelompokTani'),
            });
            $.ajax({
                url: route,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#editId').val(data.id);
                    $('#editName').val(data.name);
                    $('#editNik').val(data.nik);
                    $('#editKelompokTani').append($("<option selected></option>").val(data.kelompok_tani_id).text(data.kelompok_tani.name)).trigger('change');
                    $('#editAddress').val(data.address);
                    $('#editLuas_lahan').val(data.luas_lahan);
                }
            });
        });

        $('#editPetani').on('submit', function(e) {
            e.preventDefault();
            $('#submitEditButton').attr('disabled', true);
            var id = $('#editId').val();
            var route = "{{ route('petani.update', ':id') }}";
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
                    $('#editPetaniModal').modal('toggle');
                    $('#table-petani').DataTable().ajax.reload();
                }
            });
            $('#submitEditButton').removeAttr('disabled');
            return false;
        });
    </script>
@endsection
