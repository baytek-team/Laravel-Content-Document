<table class="ui selectable very basic table">
    <thead>
        <tr>
            <th>{{ ___('Folder Name') }}</th>
        </tr>
    </thead>
    <tbody class="dropzone-preview">
        @forelse($categories as $category)
            <tr data-category-id="{{ $category->id }}">
                <td class="resource-details">
                    <div class="ui grid">
                        @if($category->relationships()->get('content_type') == 'folder')
                            <div class="twelve wide middle aligned column">
                                <i class="folder icon"></i>
                                <a class="item" href="{{ route('document.folder.show', $category->id) }}">
                                    {{ $category->title }}
                                </a>
                            </div>
                            <div class="ui right aligned four wide column">
                                <div class="ui compact text menu" >
                                    @if($category->parent() && content($category->parent())->key != 'webpage')
                                        @can('Update Folder')
                                        <a class="item" href="{{ route('document.folder.edit', $category->id) }}">
                                            <i class="edit icon"></i>
                                            {{-- {{ ___('Edit') }} --}}
                                        </a>
                                        @endcan
                                        @can('Delete Folder')
                                        @button('', [
                                            'method' => 'delete',
                                            'location' => 'document.folder.destroy',
                                            'type' => 'route',
                                            'confirm' => 'Are you sure you want to delete this folder?<br/>All files and subfolders will be deleted as well.<br/>This action cannot be undone.',
                                            'class' => 'item action',
                                            'prepend' => '<i class="delete icon"></i>',
                                            'model' => [$category->id],
                                        ])
                                        @endcan
                                    @else
                                        @can('Update Folder')
                                        <a class="item disabled"><i class="edit icon"></i> {{-- Edit --}}</a>
                                        @endcan
                                        @can('Delete Folder')
                                        <a class="item disabled"><i class="delete icon"></i> {{-- Delete --}}</a>
                                        @endcan
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="twelve wide middle aligned column">
                                <i class="{{ \Baytek\Laravel\Content\Types\Document\Models\File::getIconCssClass($category->getMeta('original')) }}"></i>
                                <a class="item" href="{{ route('document.file.download', [$category->id]) }}">
                                    {{ $category->title }}
                                </a>
                            </div>
                            <div class="ui right aligned four wide column">
                                <div class="ui compact text menu">
                                    @can('Update File')
                                    <a class="item" href="{{ route('document.file.edit', [$category->id]) }}">
                                        <i class="edit icon"></i>
                                        {{-- {{ ___('Edit') }} --}}
                                    </a>
                                    @endcan
                                    @can('Delete File')
                                    @button('', [
                                        'method' => 'delete',
                                        'location' => 'document.file.destroy',
                                        'type' => 'route',
                                        'confirm' => 'Are you sure you want to delete this file?',
                                        'class' => 'item action',
                                        'prepend' => '<i class="delete icon"></i>',
                                        'model' => [$category->id],
                                    ])
                                    @endcan
                                </div>
                            </div>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr class="empty">
                <td colspan="3">
                    <div class="ui centered">{{ ___('There are no results') }}</div>
                </td>
            </tr>
        @endforelse
    </tbody>
    <tfoot class="dropzone-template" style="display:none">
        <tr>
            <td class="resource-details">
                <div class="ui grid">
                    <div class="twelve wide middle aligned column">
                        <i class="file outline icon"></i>
                        <span class="uploading"><strong>Uploading: </strong></span>
                        <span class="dz-error-message" data-dz-errormessage></span>
                        <a class="file-name" data-dz-name data-href="{{ route('document.file.download', 1) }}"></a>
                    </div>
                    <div class="ui right aligned four wide column">
                        <div class="ui compact text menu">
                            @can('Update File')
                            <a style="display: none" class="edit-button item" data-href="{{ route('document.file.edit', 1) }}">
                                <i class="edit icon"></i>
                            </a>
                            @endcan
                            @can('Delete File')
                            <a class="item delete-button" data-dz-remove data-href="{{ route('document.file.destroy', 1) }}" >
                                <i class="delete icon"></i>
                                <span class="delete-text"></span>
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="ui active green progress">
                    <div class="bar" data-dz-uploadprogress>
                        <div class="progress"></div>
                    </div>
                    {{-- <div class="label">Uploading <span data-dz-name></span></div> --}}
                </div>
            </td>
        </tr>
    </tfoot>
</table>

<div id="upload-dimmer" class="ui page dimmer">
    <div class="content">
        <div class="center">
            <h2 class="ui inverted icon header">
                <i class="cloud upload icon"></i>
                Drop to upload your file
                <div class="sub header">The file will start uploading automatically.</div>
            </h2>
        </div>
    </div>
</div>