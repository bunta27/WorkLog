<table class="table application-table">
    <colgroup>
        <col class="col-status">
        <col class="col-name">
        <col class="col-target">
        <col class="col-reason">
        <col class="col-applied">
        <col class="col-detail">
    </colgroup>

    <tr class="table__row">
        <th class="table__header">
            <p class="table__header--item">状態</p>
        </th>
        <th class="table__header">
            <p class="table__header--item">名前</p>
        </th>
        <th class="table__header">
            <p class="table__header--item">対象日時</p>
        </th>
        <th class="table__header">
            <p class="table__header--item">申請理由</p>
        </th>
        <th class="table__header">
            <p class="table__header--item">申請日時</p>
        </th>
        <th class="table__header">
            <p class="table__header--item">詳細</p>
        </th>
    </tr>

    @foreach($applications as $application)
        @if($application['approval_status'] === $status)
            <tr class="table__row">
                <td class="table__description col-status">
                    <p class="table__description--item">{{ $application['approval_status'] }}</p>
                </td>
                <td class="table__description col-name">
                    <p class="table__description--item">{{ $user->name }}</p>
                </td>
                <td class="table__description">
                    <p class="table__description--item">{{ $application['date'] }}</p>
                </td>
                <td class="table__description col-reason">
                    <p class="table__description--item">{{ $application['comment'] ?? 'なし' }}</p>
                </td>
                <td class="table__description">
                    <p class="table__description--item">{{ $application['application_date'] }}</p>
                </td>
                <td class="table__description col-detail">
                    <a class="table__item--detail--link"
                        href="{{ url('/attendance/detail/' . $application['attendance_record_id']) }}">
                        詳細
                    </a>
                </td>
            </tr>
        @endif
    @endforeach
</table>
