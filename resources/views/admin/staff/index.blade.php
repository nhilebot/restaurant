@extends('layouts.admin')

@section('content')
    <h1 class="text-3xl font-bold mb-5">Danh sách nhân viên</h1>
    <div class="bg-white p-5 shadow rounded">
        <table class="w-full border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">Tên</th>
                    <th class="border p-2">Vai trò</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border p-2">Nguyễn Văn An</td>
                    <td class="border p-2">Quản lý kho</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection