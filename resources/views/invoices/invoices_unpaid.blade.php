@extends('layouts.master')
@section('css')
    <!-- Internal Data table css -->
    <link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>

@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/الفواتير غير المدفوعة</span>
						</div>
					</div>

				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
    @if(session()->has('Add'))
{{--        <div class="alert alert-success alert-dismissible fade show" role="alert">--}}
{{--            <strong>{{session()->get('Add')}}</strong>--}}
{{--            <button type="button" class="close" data-dismiss="alert" aria-label="close">--}}
{{--                <span aria-hidden="true">&times;</span>--}}
{{--            </button>--}}
{{--        </div>--}}
<script>
    window.onload = function() {
        notif({
            msg: "تم اضافة الفاتورة بنجاح",
            type: "success"
        })
    }

</script>
    @endif

    @if(session()->has('archive'))
        <script>
            window.onload = function() {
                notif({
                    msg: "تم أرشفة الفاتورة بنجاح",
                    type: "warning"
                })
            }

        </script>
    @endif
    @if(session()->has('delete'))
        <script>
            window.onload = function() {
                notif({
                    msg: "تم حذف الفاتورة بنجاح",
                    type: "warning"
                })
            }

        </script>
    @endif
    @if (session()->has('Edit'))
        <script>
            window.onload = function() {
                notif({
                    msg: "تم تعديل الفاتورة بنجاح",
                    type: "warning"
                })
            }

        </script>
    @endif

    <!-- row -->
				<div class="row">


                        <!--/div-->

                        <!--div-->

                        <!--/div-->

                        <!--div-->
                    <div class="col-xl-12">
                        <div class="card mg-b-20">
                            <div class="card-header pb-0">
                                <div class="col-sm-6 col-md-4 col-xl-3 mg-t-20">


                                </div>


                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table text-md-nowrap" id="example1" data-page-length="50">
                                        <thead>
                                        <tr>
                                            <th class="border-bottom-0">#</th>
                                            <th class="border-bottom-0">رقم الفاتورة</th>
                                            <th class="border-bottom-0">تاريخ الفاتورة</th>
                                            <th class="border-bottom-0">تاريخ الاستحقاق</th>
                                            <th class="border-bottom-0">المنتج</th>
                                            <th class="border-bottom-0">القسم</th>

                                            <th class="border-bottom-0">الخصم</th>
                                            <th class="border-bottom-0">نسبة الضريبة</th>
                                            <th class="border-bottom-0">قيمة الضريبة</th>
                                            <th class="border-bottom-0">الإجمالي</th>
                                            <th class="border-bottom-0">الحالة</th>
                                            <th class="border-bottom-0">ملاحظات</th>
                                            <th class="border-bottom-0">العمليات</th>




                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php  ?>
                                        @php
                                            $i=0
                                        @endphp

                                        @foreach($invoices as $invoice)
                                            <tr>
                                                @php
                                                    $i++
                                                @endphp
                                                <td>{{$i}}</td>
                                                <td>{{$invoice->invoice_number}}</td>
                                                <td>{{$invoice->invoice_date}}</td>
                                                <td>{{$invoice->due_date}}</td>
                                                <td>{{$invoice->product}}</td>
                                                <td><a href="{{route('invoices_all.show' , $invoice->id)}}">{{$invoice->sections->section_name}}</a></td>
                                                <td>{{$invoice->discount}}</td>
                                                <td>{{$invoice->rate_vat}}</td>
                                                <td>{{$invoice->value_vat}}</td>
                                                <td>{{$invoice->total}}</td>
                                                <td>
                                                    @if($invoice->value_status == 1)
                                                        <span class="text-success">{{$invoice->status}}</span>
                                                    @elseif($invoice->value_status == 2)
                                                        <span class="text-danger">{{$invoice->status}}</span>
                                                    @else
                                                        <span class="text-warning ">{{$invoice->status}}</span>
                                                    @endif
                                                </td>
                                                <td>{{$invoice->note}}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button aria-expanded="false" aria-haspopup="true" class="btn ripple btn-info" data-toggle="dropdown" type="button">العمليات<i class="fas fa-caret-down mr-1"></i></button>
                                                        <div class="dropdown-menu tx-13">
                                                            @can('تعديل الفاتورة')
                                                                <a class="dropdown-item" href="{{route('invoices_all.edit' ,$invoice->id)}}">تعديل الفاتورة</a>
                                                            @endcan
                                                            {{--                                                        <a class="dropdown-item" href="{{route('add_archive' ,$invoice->id)}}">أرشفة الفاتورة</a>--}}
                                                            @can('ارشفة الفاتورة')
                                                                <a class="dropdown-item" href="#" data-invoice_id="{{ $invoice->id }}"
                                                                   data-toggle="modal" data-target="#Transfer_invoice"><i
                                                                        class="text-warning fas fa-exchange-alt"></i>&nbsp;&nbsp;نقل الي
                                                                    الارشيف</a>
                                                            @endcan
                                                            @can('حذف الفاتورة')
                                                                <a class="dropdown-item" href="#" data-invoice_id="{{ $invoice->id }}"
                                                                   data-toggle="modal" data-target="#delete_invoice"><i
                                                                        class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;حذف
                                                                    الفاتورة</a>
                                                            @endcan
                                                            @can('تغير حالة الدفع')
                                                                <a class="dropdown-item"
                                                                   href="{{route('status_show',$invoice->id)}}"><i
                                                                        class=" text-success fas
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    fa-money-bill"></i>&nbsp;&nbsp;تغير
                                                                    حالة
                                                                    الدفع</a>
                                                            @endcan
                                                            @can('طباعةالفاتورة')
                                                                <a class="dropdown-item" href="{{route('print_invoice', $invoice->id)}}"><i
                                                                        class="text-success fas fa-print"></i>&nbsp;&nbsp;طباعة
                                                                    الفاتورة
                                                                </a>
                                                            @endcan
                                                        </div>
                                                    </div>

                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                        <!--/div-->

                    <div class="modal fade" id="delete_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">حذف الفاتورة</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <form action="{{ route('invoices_all.destroy', 'test') }}" method="post">
                                   @csrf
                                    @method('delete')
                                </div>
                                <div class="modal-body">
                                    هل انت متاكد من عملية الحذف ؟
                                    <input type="hidden" name="invoice_id" id="invoice_id" value="">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                                    <button type="submit" class="btn btn-danger">تاكيد</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>

{{--////////////////////////////--}}
                    <div class="modal fade" id="Transfer_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">ارشفة الفاتورة</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <form action="{{ route('invoices_all.destroy', 'test') }}" method="post">
                                @csrf
                                    @method('delete')
                                </div>
                                <div class="modal-body">
                                    هل انت متاكد من عملية الارشفة ؟
                                    <input type="hidden" name="invoice_id" id="invoice_id" value="">
                                    <input type="hidden" name="id_page" id="id_page" value="2">

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                                    <button type="submit" class="btn btn-success">تاكيد</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
				<!-- row closed -->
                </div>
                <!-- Container closed -->
                </div>
			<!-- Container closed -->

		<!-- main-content closed -->
@endsection
@section('js')
    <script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
    <!--Internal  Datatable js -->
    <script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/notify/js/notifit-custom.js')}}"></script>
    <script src="{{URL::asset('assets/js/table-data.js')}}"></script>

    <script>
        $('#delete_invoice').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var invoice_id = button.data('invoice_id')
            var modal = $(this)
            modal.find('.modal-body #invoice_id').val(invoice_id);
        })

    </script>

    <script>
        $('#Transfer_invoice').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var invoice_id = button.data('invoice_id')
            var modal = $(this)
            modal.find('.modal-body #invoice_id').val(invoice_id);
        })

    </script>
@endsection
