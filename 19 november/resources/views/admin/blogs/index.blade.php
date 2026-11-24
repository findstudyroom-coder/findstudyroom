@extends('admin.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-white">📝 Blogs</h2>
    <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">+ Add Blog</a>
</div>

<div class="card table-card border-0 mt-3">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Published</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blogs as $blog)
                        <tr>
                            <td style="width: 120px;">
                                @if($blog->featured_image)
                                    <img src="{{ asset('public/uploads/blogs/'.$blog->featured_image) }}" alt="{{ $blog->title }}" style="height:50px; width:80px; object-fit:cover; border-radius:6px;">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td class="fw-bold">{{ $blog->title }}</td>
                            <td>{{ $blog->author_name ?? 'Admin' }}</td>
                            <td>
                                @if($blog->status === 'published')
                                    <span class="badge bg-success">Published</span>
                                @else
                                    <span class="badge bg-secondary">Draft</span>
                                @endif
                            </td>
                            <td>{{ optional($blog->published_at)->format('d M Y') ?? '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-warning btn-sm">✏️</a>
                                <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">🗑</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No blogs found yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
