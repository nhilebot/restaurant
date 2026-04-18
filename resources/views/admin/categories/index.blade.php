@extends('layouts.admin')

@section('title', 'Quản lý danh mục')
@section('page_title', 'Danh mục')

@section('topbar_actions')
    <a href="{{ route('admin.categories.create') }}" class="button button-primary">Thêm danh mục</a>
@endsection

@section('content')
    <section class="panel">
        <div class="panel-header">
            <div class="panel-heading">Danh sách danh mục</div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>Số thứ tự</th>
                            <th>Tên danh mục</th>
                            <th>Hình ảnh</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $category->name }}</td>
                                <td>
                                    @if($category->image)
                                        <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                    @else
                                        <span class="text-muted">Không có ảnh</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="button button-sm">Sửa</a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="button button-sm button-danger" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center; padding: 24px; color: #667085;">Chưa có danh mục nào</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection