@extends('adminlte::page')

@section('title', 'وجهة | المستخدمين')

@section('content_header')
    <h1>المستخدمين</h1>
@stop

@section('content')
   <div class="col-md-12">
    <div class="card">
        <!-- /.card-header -->
        <div class="card-body p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>الاسم</th>
                        <th>رقم الجوال</th>
                        <th>نوع المستخدم</th>
                        <th>حالة الحظر</th>
                        <th style="width: 150px">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->phone ?? 'غير محدد' }}</td>
                        <td>
                            @switch($user->user_type)
                                @case('admin')
                                    <span class="badge bg-danger">مدير</span>
                                    @break
                                @case('owner')
                                    <span class="badge bg-warning">صاحب منشأة</span>
                                    @break
                                @default
                                    <span class="badge bg-success">مستخدم عادي</span>
                            @endswitch
                        </td>
                        <td>
                            @if($user->is_banned)
                                <span class="badge bg-danger">محظور</span>
                            @else
                                <span class="badge bg-success">نشط</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
            {{ $users->links() }}
        </div>
    </div>
</div>
@stop
