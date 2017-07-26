<style>
        .resource-details {
            display: block;
            position: relative;
        }
        .resource-details .ui.progress {
            position:absolute;
            top: 0;
            left: 0;
            height: 3px;
            overflow: hidden;
            width: 100%;
            padding:0;
            margin:0;
        }
        .resource-details .ui.progress .bar {
            height: 3px;
            padding:0;
            margin:0;
        }
    </style>

    <table class="ui selectable compact table ">
        <thead>
            <tr>
                <th>{{ ___('Folder Name') }}</th>
            </tr>
        </thead>
        <tbody class="dropzone-preview">
            @forelse($categories as $category)
                <tr data-category-id="{{ $category->id }}">
                    <td class="resource-details">
                        @if($category->relationships()->get('content_type') == 'resource-category')
                            <i class="folder icon"></i>
                            <a class="item" href="{{ route('document.folder.show', $category->id) }}">
                                {{ $category->title }}
                            </a>
                            <div style="float: right">
                                <div class="ui compact text menu">
                                    @if($category->parent() && content($category->parent())->key != 'webpage')
                                        <a class="item" href="{{ route('document.folder.edit', $category->id) }}">
                                            <i class="pencil icon"></i>
                                            {{ ___('Edit') }}
                                        </a>
                                        @button(___('Delete'), [
                                            'method' => 'delete',
                                            'location' => 'document.folder.destroy',
                                            'type' => 'route',
                                            'confirm' => 'Are you sure you want to delete this folder?<br/>All files and subfolders will be deleted as well.<br/>This action cannot be undone.',
                                            'class' => 'item action',
                                            'prepend' => '<i class="delete icon"></i>',
                                            'model' => [$category->id],
                                        ])
                                    @else
                                        <a class="item disabled"><i class="pencil icon"></i> Edit</a>
                                        <a class="item disabled"><i class="delete icon"></i> Delete</a>
                                    @endif
                                </div>
                            </div>
                        @else
                            <i class="{{ \Baytek\Laravel\Content\Types\Document\Models\File::getIconCssClass($category->getMeta('original')) }}"></i>
                            <a class="item" href="{{ route('document.file.download', [$category->id]) }}">
                                {{ $category->title }}
                            </a>
                            <div style="float: right">
                                <div class="ui compact text menu">
                                    <a class="item" href="{{ route('document.file.edit', [$category->id]) }}">
                                        <i class="pencil icon"></i>
                                        {{ ___('Edit') }}
                                    </a>
                                    @button(___('Delete'), [
                                        'method' => 'post',
                                        'location' => 'document.file.delete',
                                        'type' => 'route',
                                        'confirm' => 'Are you sure you want to delete this file?',
                                        'class' => 'item action',
                                        'prepend' => '<i class="delete icon"></i>',
                                        'model' => [$category->id],
                                    ])
                                </div>
                            </div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">
                        <div class="ui centered">{{ ___('There are no results') }}</div>
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot class="dropzone-template" style="display:none">
            <tr>
                <td class="resource-details">
                    <i class="file outline icon"></i>
                    <span class="uploading"><strong>Uploading: </strong></span>
                    <span class="dz-error-message" data-dz-errormessage></span>
                    <a class="file-name" data-dz-name data-href="{{ route('document.file.download', 1) }}"></a>
                    {{-- <div class="item dz-size" data-dz-size></div> --}}

                    <div style="float: right">
                        <div class="ui compact text menu">
                            <a style="display: none" class="edit-button item" data-href="{{ route('document.file.edit', 1) }}">
                                <i class="pencil icon"></i>
                                {{ ___('Edit') }}
                            </a>
                            <a class="item delete-button" data-dz-remove data-href="{{ route('document.file.delete', 1) }}" >
                                <i class="delete icon"></i>
                                <span class="delete-text">{{ ___('Remove') }}</span>
                            </a>
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