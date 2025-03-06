@extends('layouts.app')

@section('content')
    <div class="container mx-auto max-w-lg p-6 bg-white shadow-md rounded-lg">
        <h1 class="text-xl font-semibold mb-4">My Links</h1>

        <div class="flex gap-2 mb-4">
            <input type="url" id="url-input" required placeholder="Enter URL"
                   class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300">
            <button id="shorten-btn" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                Shorten
            </button>
        </div>

        <ul id="links-list" class="space-y-2">
            @foreach ($links as $link)
                <li class="flex items-center justify-between p-2 border rounded-md">
                    <a href="{{ route('links.redirect', $link->short_code) }}" target="_blank" class="text-blue-500 hover:underline">
                        {{ url('/link/' . $link->short_code) }}
                    </a>
                    <span class="text-gray-500 text-sm">
                        (<span class="clicks " data-id="{{ $link->id }}">{{ $link->clicks }}</span> clicks)
                    </span>
                    <button class="delete-btn text-red-500 hover:text-red-700" data-id="{{ $link->id }}">Delete</button>
                </li>
            @endforeach
        </ul>
    </div>
@endsection

@section('custom-script')
    <script>
        document.getElementById('shorten-btn').addEventListener('click', function () {
            let urlInput = document.getElementById('url-input').value;
            if (!urlInput) return alert('Please enter a URL');

            axios.post("{{ route('links.store') }}", {
                original_url: urlInput
            }).then(response => {
                let link = response.data;
                let newLink = `
                <li class="flex items-center justify-between p-2 border rounded-md">
                    <a href="${link.short_url}" target="_blank" class="text-blue-500 hover:underline">${link.short_url}</a>
                    <span class="text-gray-500 text-sm">(0 clicks)</span>
                    <button class="delete-btn text-red-500 hover:text-red-700" data-id="${link.id}">Delete</button>
                </li>`;
                document.getElementById('links-list').innerHTML += newLink;
                document.getElementById('url-input').value = '';
            }).catch(error => {
                alert('Error: ' + error.response.data.message);
            });
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('delete-btn')) {
                let linkId = e.target.getAttribute('data-id');

                axios.delete(`/delete/${linkId}`).then(() => {
                    e.target.closest('li').remove();
                }).catch(error => {
                    alert('Delete failed: ' + error.response.data.message);
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            function updateClickCounts() {
                axios.get("{{ route('links.getClicks') }}")
                    .then(response => {
                        let clicksData = response.data;
                        clicksData.forEach(link => {
                            let span = document.querySelector(`.clicks[data-id='${link.id}']`);
                            if (span) {
                                span.textContent = link.clicks;
                            }
                        });
                    })
                    .catch(console.error);
            }

            setInterval(updateClickCounts, 10000);
        });
    </script>
@endsection
