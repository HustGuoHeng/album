<!-- Page-->
<div class="page" data-page="file-list">
    {{--<div class="fab fab-right-bottom fab-morph" data-morph-to=".toolbar"><a href="#"><i class="icon f7-icons ios-only">add</i><i class="icon f7-icons ios-only">close</i><i class="icon material-icons md-only">add</i><i class="icon material-icons md-only">close</i></a></div>--}}
    <!-- Top Navbar-->
        <div class="navbar">
            <div class="navbar-inner">
                <div class="left"><a href="" class="back">Back</a></div>
                <div class="center sliding">{{ $dirInfo['name'] }}</div>
                <div class="right"><a href="#" class="open-panel link icon-only"><i class="icon icon-bars"></i></a>
                </div>
            </div>
        </div>
    <div class="page-content">
        <div class="list-block">
            @include('album/fileList', ['data' => $data])
        </div>

    </div>

</div>