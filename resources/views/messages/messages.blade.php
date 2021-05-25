@extends('layout.index')

@section('content')
    <div class="container-fluid p-0">
        <br>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" href="">{{ __('Unread ') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">{{ __('All') }}</a>
            </li>
        </ul>
        <section class="resume-section" id="about">
            <div class="resume-section-content">

                <div class="col-sm-9 col-xs-12 chat" style="overflow: hidden; outline: none;" tabindex="5001">
                    <div class="col-inside-lg decor-default">
                        <div class="chat-body">
                            <h6>Mini Chat</h6>
                            <div class="answer left">
                                <div class="avatar">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="User name">
                                    <div class="status offline"></div>
                                </div>
                                <div class="name">Alexander Herthic</div>
                                <div class="text">
                                    Lorem ipsum dolor amet, consectetur adipisicing elit Lorem ipsum dolor amet, consectetur adipisicing elit Lorem ipsum dolor amet, consectetur adiping elit
                                </div>
                                <div class="time">5 min ago</div>
                            </div>
                            <div class="answer right">
                                <div class="avatar">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="User name">
                                    <div class="status offline"></div>
                                </div>
                                <div class="name">Alexander Herthic</div>
                                <div class="text">
                                    Lorem ipsum dolor amet, consectetur adipisicing elit Lorem ipsum dolor amet, consectetur adipisicing elit Lorem ipsum dolor amet, consectetur adiping elit
                                </div>
                                <div class="time">5 min ago</div>
                            </div>
                            <div class="answer left">
                                <div class="avatar">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="User name">
                                    <div class="status online"></div>
                                </div>
                                <div class="name">Alexander Herthic</div>
                                <div class="text">
                                    ...
                                </div>
                                <div class="time">5 min ago</div>
                            </div>
                            <div class="answer right">
                                <div class="avatar">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="User name">
                                    <div class="status busy"></div>
                                </div>
                                <div class="name">Alexander Herthic</div>
                                <div class="text">
                                    It is a long established fact that a reader will be. Thanks Mate!
                                </div>
                                <div class="time">5 min ago</div>
                            </div>
                            <div class="answer right">
                                <div class="avatar">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="User name">
                                    <div class="status off"></div>
                                </div>
                                <div class="name">Alexander Herthic</div>
                                <div class="text">
                                    It is a long established fact that a reader will be. Thanks Mate!
                                </div>
                                <div class="time">5 min ago</div>
                            </div>
                            <div class="answer left">
                                <div class="avatar">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="User name">
                                    <div class="status offline"></div>
                                </div>
                                <div class="name">Alexander Herthic</div>
                                <div class="text">
                                    Lorem ipsum dolor amet, consectetur adipisicing elit Lorem ipsum dolor amet, consectetur adipisicing elit Lorem ipsum dolor amet, consectetur adiping elit
                                </div>
                                <div class="time">5 min ago</div>
                            </div>
                            <div class="answer right">
                                <div class="avatar">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="User name">
                                    <div class="status offline"></div>
                                </div>
                                <div class="name">Alexander Herthic</div>
                                <div class="text">
                                    Lorem ipsum dolor amet, consectetur adipisicing elit Lorem ipsum dolor amet, consectetur adipisicing elit Lorem ipsum dolor amet, consectetur adiping elit
                                </div>
                                <div class="time">5 min ago</div>
                            </div>
                            <div class="answer left">
                                <div class="avatar">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="User name">
                                    <div class="status online"></div>
                                </div>
                                <div class="name">Alexander Herthic</div>
                                <div class="text">
                                    ...
                                </div>
                                <div class="time">5 min ago</div>
                            </div>
                            <div class="answer right">
                                <div class="avatar">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="User name">
                                    <div class="status busy"></div>
                                </div>
                                <div class="name">Alexander Herthic</div>
                                <div class="text">
                                    It is a long established fact that a reader will be. Thanks Mate!
                                </div>
                                <div class="time">5 min ago</div>
                            </div>
                            <div class="answer right">
                                <div class="avatar">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="User name">
                                    <div class="status off"></div>
                                </div>
                                <div class="name">Alexander Herthic</div>
                                <div class="text">
                                    It is a long established fact that a reader will be. Thanks Mate!
                                </div>
                                <div class="time">5 min ago</div>
                            </div>
                            <div class="answer-add">
                                <input placeholder="Write a message">
                                <span class="answer-btn answer-btn-1"></span>
                                <span class="answer-btn answer-btn-2"></span>
                            </div>
                        </div>
                    </div>

            </div>
        </section>
@endsection
@section('javascript')

@endsection
