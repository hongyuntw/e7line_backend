
@include('mails.index', ['concat_persons' => $concat_persons])

<table>
    <thead>
    <tr>
        <th>姓名</th>
        <th>公司(客戶)</th>
        <th>縣市地區</th>
        <th>Email</th>
        <th>在職狀態</th>
        <th>收信意願</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($concat_persons as $concat_person)
        <tr>
            <td>{{ $concat_person->name }}</td>
            <td>{{ $concat_person->customer_name }}</td>
            <td>{{$concat_person->city}}{{$concat_person->area}}</td>
            <td>{{ $concat_person->email  }}</td>
            <td>{{$concat_person->is_left}}</td>
            <td>{{$concat_person->want_receive_mail}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
