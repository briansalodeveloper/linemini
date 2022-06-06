<table class="table text-nowrap">
    <thead>
        <tr>
            <th width="10"></th>
            <th>{{ __('words.Id') }}</th>
            <th>{{ __('words.Title') }}</th>
            <th>{{ __('words.Status') }}</th>
            <th>{{ __('words.TopView') }}</th>
            <th>{{ __('words.DisplayTargetPerson') }}</th>
            <th>{{ __('words.StartDateTime') }}</th>
            <th>{{ __('words.EndDateTime') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $datum)
            @php
                $route = '';

                if ($contentType == Globals::mContentPlan()::CONTENTTYPE_NOTICE) {
                    $route = route('notice.edit', $datum['contentPlanId']);
                } elseif ($contentType == Globals::mContentPlan()::CONTENTTYPE_RECIPE) {
                    $route = route('recipe.edit', $datum['contentPlanId']);
                } elseif ($contentType == Globals::mContentPlan()::CONTENTTYPE_PRODUCTINFO) {
                    $route = route('productInformation.edit', $datum['contentPlanId']);
                } elseif ($contentType == Globals::mContentPlan()::CONTENTTYPE_COLUMN) {
                    $route = route('column.edit', $datum['contentPlanId']);
                }
            @endphp
            <tr>
                <td>
                    @if(!empty($route))
                        <a href="{{ $route }}" onclick-showloading><button class="btn btn-01">{{ __('words.Edit') }}</button></a>
                    @endif
                </td>
                <td class="id">{{ $datum->id }}</td>
                <td>{{ $datum->openingLetter }}</td>
                <td>{{ $datum->statusStr }}</td>
                <td>{{ $datum->contentTypeNewsStr }}</td>
                <td>{{ $datum->displayTargetFlgStr }}</td>
                <td>{{ $datum->formatDate('startDateTime', 'Y/m/d H:i') }}</td>
                <td>{{ $datum->formatDate('endDateTime', 'Y/m/d H:i') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>