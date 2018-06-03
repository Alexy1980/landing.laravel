<div style="margin:0px 50px 0px 50px;">

    @if($portfolios)

        <table class="table table-hover table-striped">
            <thead>
            <tr>
                <th>№ п/п</th>
                <th>Имя</th>
                <th>Дата создания</th>

                <th>Удалить</th>
            </tr>
            </thead>
            <tbody>

            @foreach($portfolios as $k => $portfolio)

                <tr>

                    <td>{{ $portfolio->id }}</td>
                    <td>{!! Html::link(route('portfoliosEdit',['portfolio'=>$portfolio->id]),$portfolio->name,['alt'=>$portfolio->name]) !!}</td>

                    <td>{{ $portfolio->created_at }}</td>

                    <td>
                        {!! Form::open(['url'=>route('portfoliosEdit',['portfolio'=>$portfolio->id]), 'class'=>'form-horizontal','method' => 'POST']) !!} <!--передаем метод POST, хотя использоваться будет метод DELETE, т.к. будет передан экшн delete в поле
                        hidden, используем хелпер method_field()-->

                        {{method_field('DELETE')}}
                        {!! Form::button('Удалить',['class'=>'btn btn-danger','type'=>'submit']) !!}

                        {!! Form::close() !!}
                    </td>
                </tr>

            @endforeach


            </tbody>
        </table>
    @endif

    {!! Html::link(route('portfoliosAdd'),'Новое портфолио') !!}

</div>