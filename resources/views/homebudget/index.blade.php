<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>家計簿アプリ</title>
        <link rel="stylesheet" href="{{asset('css/style.css')}}">
        <script src="https://kit.fontawesome.com/5b69d558cb.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <header>
            <h1>家計簿アプリ</h1>
        </header>
        <section class="container">
            <div class="balance">
                <h3>支出一覧</h3>
                <form method="get" action="{{ route('index') }}" class="search_container">
                    <input type="text" size="25" placeholder="キーワード検索" name ="keyword" value="{{$keyword}}">
                    <input type="submit" value="&#xf002">
                    <div class="search_container_dates">
                        <div class="search_container_date">
                            <input type="date" name="startDate">
                        </div>
                        <div class="search_container_date">
                            <input type="date" name="endDate">
                        </div>
                    </div>
                </form>
                @if(session('flash_message'))
                    <div class="flash_message">
                        {{ session('flash_message') }}
                    </div>
                @endif
                @if(session('flash_error_message'))
                    <div class="flash_error_message">
                        {{ session('flash_error_message') }}
                    </div>
                @endif
                <table>
                    <thead>
                        <tr>
                            <th>日付</th>
                            <th>カテゴリ</th>
                            <th>金額</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- 支出データのループ処理 -->
                        @foreach($homebudgets as $homebudget)
                        <tr>
                            <td>{{$homebudget->date}}</td>
                            <td>{{$homebudget->category->name}}</td>
                            <td>
                                @if($homebudget->category->name == '収入')
                                    <span class="income">
                                @else
                                    <span class="payment">
                                @endif
                                {{$homebudget->price}}</span>
                            </td>
                            <td class="button-td">
                                <form action="{{ route('homebudget.edit', ['id' => $homebudget->id]) }}" method="">
                                    <input type="submit" value="更新" class="edit-button">
                                </form>
                                <form action="{{ route('homebudget.destroy', ['id' => $homebudget->id]) }}" method="POST">
                                    @csrf
                                    <input type="submit" value="削除" class="delete-button">
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="flex">
                    <div class="pagination">
                        {{$homebudgets->links()}}
                    </div>
                    <div class="flex total">
                        <p>収支合計:{{$income}}円</p>
                        <p>収支合計:{{$payment}}円</p>
                    </div>
                </div>
            </div>
            <div class="add-balance">
                <h3>支出の追加</h3>
                <form action="{{ route('store') }}" method="POST">
                    @csrf
                    <label for="date">日付:</label>
                    <input type="date" id="date" name="date">
                    @if($errors->has('date')) <span class="error">{{$errors->first('date')}}</span> @endif
                    <label for="category">カテゴリ:</label>
                    <select name="category" id="category">
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('category')) <span class="error">{{$errors->first('category')}}</span> @endif

                    <label for="price">金額:</label>
                    <input type="text" id="price" name="price">
                    @if($errors->has('price')) <span class="error">{{$errors->first('price')}}</span> @endif

                    <button type="submit">追加</button>
                </form>
            </div>
        </section>
    </body>
</html>