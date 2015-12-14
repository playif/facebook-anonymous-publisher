QueueRows = React.createClass(
  convertTimestamp: (timestamp) ->
    date = new Date(timestamp * 1000)
    year = date.getFullYear()
    month = '0' + (date.getMonth() + 1)
    day = '0' + date.getDate()
    hours = '0' + date.getHours()
    minutes = '0' + date.getMinutes()
    seconds = '0' + date.getSeconds()
    time = year + '-' + month.substr(-2)  + '-' + day.substr(-2)  + ' ' + hours.substr(-2) + ':' + minutes.substr(-2) + ':' + seconds.substr(-2)

  getPostTypeText: (type) ->
    switch type
      when 1 then text = @props.config.plaintext
      when 2 then text = @props.config.link
      when 3 then text = @props.config.image
    text

  getPostStateText: (state) ->
    switch state
      when 0 then state = @props.config.queuing
      when 1 then state = @props.config.published
      when 2 then state = @props.config.unpublished
      when 4 then state = @props.config.deny
      when 5 then state = @props.config.pending
      when 8 then state = @props.config.idle
      when 9 then state = @props.config.failed
    state

  getActionButton: (id, state) ->
    if state is 5
      return `<div><div className="btn-group" role="group"><ActionButton config={this.props.config} onClick={this.handleButtonClick.bind(this, id, 'deny')} id={id} type={'deny'} style={'danger'} /><ActionButton config={this.props.config} onClick={this.handleButtonClick.bind(this, id, 'allow')} id={id} type={'allow'} style={'success'} /></div> <ActionButton config={this.props.config} onClick={this.handleButtonClick.bind(this, id, 'block')} type={'block'} style={'default'} /></div>`
    else
      style = 'default'
      switch state
        when 0
          type = 'deny'
          style = 'danger'
        when 1 then type = 'unpublish'
        when 2, 4, 8, 9
          type = 'republish'
          style = 'success'
      return `<div><ActionButton config={this.props.config} onClick={this.handleButtonClick.bind(this, id, type)} type={type} style={style} /> <ActionButton config={this.props.config} onClick={this.handleButtonClick.bind(this, id, 'block')} type={'block'} style={'default'} /></div>`

  getRowClassName: (state) ->
    switch state
      when 0 then state = 'queuing'
      when 1 then state = 'published'
      when 2 then state = 'unpublished'
      when 4 then state = 'deny'
      when 5 then state = 'pending'
      when 8 then state = 'idle'
      when 9 then state = 'failed'
    return 'state-' + state

  handleButtonClick: (id, type)->
    if type is 'block'
      check = confirm 'Are you sure?'
      if !check then return

    $.ajax
      headers: 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      url: '/admin/action/' + type + '/' + id
      dataType: 'json'
      type: 'post'
      cache: false
      success: ((result) ->
        @props.onChange()
        type = if result.state is 'success' then 'success' else 'danger'
        $.notify { message: result.message }, { placement: { from: "bottom" }, type: type}
      ).bind(@)
      error: ((xhr, status, err) ->
        $.notify { message: 'Something wrong.' }, { placement: { from: "bottom" }, type: 'danger'}
        console.error @props.url, status, err.toString()
      ).bind(@)

  handleSearchSubmit: (keyword) ->
    @props.onSearchSubmit keyword

  handlePageChange: (page) ->
    @props.onPageChange page

  handleRefreshButtonClick: ->
    @props.onChange()

  render: ->
    if @props.data.result isnt undefined and @props.data.result.length
      _ = @
      postNodes = @props.data.result.map((post) ->
        type = _.getPostTypeText post.post_type
        state = _.getPostStateText post.post_state
        date = _.convertTimestamp post.insert_time
        action = _.getActionButton post.id, post.post_state
        classname = _.getRowClassName post.post_state

        return `(
          <tr className={classname} key={post.id}>
            <td className="text-center"><a href={'/' + post.post_key} target="_blank">{post.id}</a></td>
            <td className="text-center">{state}</td>
            <td className="text-center">{type}</td>
            <td className="td-message"><p dangerouslySetInnerHTML={{__html: post.post_message}} /></td>
            <td className="text-center">{date}</td>
            <td className="text-center td-action">{action}</td>
          </tr>
        )`
      )
    else
      postNodes = `(
        <tr key="null">
          <td className="text-center td-empty" colSpan="6">
            {this.props.config.empty}
          </td>
        </tr>
      )`

    return `(
      <table className="table table-hover table-bordered">
        <thead className="text-center">
          <tr>
            <td colSpan="6">
              <h4 className="pull-left">
                {this.props.config.queue_list} &nbsp;
                <small className="glyphicon glyphicon-refresh refresh-button" onClick={this.handleRefreshButtonClick}></small>
              </h4>
              <SearchForm onSearchSubmit={this.handleSearchSubmit} config={this.props.config}  />
            </td>
          </tr>
          <tr>
            <td>{this.props.config.id}</td>
            <td>{this.props.config.state}</td>
            <td>{this.props.config.type}</td>
            <td className="td-message">{this.props.config.message}</td>
            <td>{this.props.config.insert_time}</td>
            <td>{this.props.config.actions}</td>
          </tr>
        </thead>
        <tbody className="queue-row">
          {postNodes}
        </tbody>
        <tfoot>
          <tr>
            <td className="text-center" colSpan="6">
              <Pagination data={this.props.data} onPageChange={this.handlePageChange} />
            </td>
          </tr>
        </tfoot>
      </table>
    )`
)

ActionButton = React.createClass(
  render: ->
    text = @props.config[@props.type]
    return `(
      <button type="button" onClick={this.props.onClick} className={'btn btn-sm btn-' + this.props.style}>{text}</button>
    )`
)

Pagination = React.createClass(
  handleClick: (page)->
    @props.onPageChange page

  render: ->
    pages = []
    if @props.data.last_page > 1
      for i in [1 .. @props.data.last_page]
        if i is @props.data.current_page
          pages.push(`<li className="active" key={i}><a href="#">{i}</a></li>`)
        else
          pages.push(`<li key={i}><a href="#" onClick={this.handleClick.bind(this, i)}>{i}</a></li>`)
    else
      pages.push(`<li className="active" key={'1'}><a href="#">1</a></li>`)

    return `(
      <nav>
        <ul className="pagination">
          {pages}
        </ul>
      </nav>
    )`
)

SearchForm = React.createClass(
  handleSubmit: (e) ->
    e.preventDefault()
    keyword = React.findDOMNode(@refs.keyword).value.trim()
    @props.onSearchSubmit
      keyword: keyword

  render: ->
    return `(
      <form className="form-inline pull-right">
        <div className="input-group">
          <input type="text" ref="keyword" className="form-control" placeholder={this.props.config.search} onKeyUp={this.handleSubmit}/>
        </div>
      </form>
    )`
)

QueueList = React.createClass(
  loadConfigFromServer: ->
    $.ajax
      headers: 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      url: '/admin/ajax/config.json'
      dataType: 'json'
      type: 'post'
      cache: false
      success: ((config) ->
        @setState config: config
      ).bind(@)
      error: ((xhr, status, err) ->
        $.notify { message: 'Something wrong. '+err.toString() }, { placement: { from: "bottom" }, type: 'danger'}
        console.error @props.url, status, err.toString()
      ).bind(@)

  loadPostsFromServer: (keyword, page = 1)->
    $.ajax
      headers: 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      url: '/admin/ajax/queue.json?page='+page
      dataType: 'json'
      type: 'post'
      data:
        keyword
      cache: false
      success: ((data) ->
        @setState data: data
      ).bind(@)
      error: ((xhr, status, err) ->
        $.notify { message: 'Something wrong. '+err.toString() }, { placement: { from: "bottom" }, type: 'danger'}
        console.error @props.url, status, err.toString()
      ).bind(@)

  getInitialState: ->
    {
      keyword: ''
      config: []
      data: []
    }

  componentDidMount: ->
    @loadConfigFromServer()
    @loadPostsFromServer()
    setInterval @loadPostsFromServer, @props.pollInterval

  changeHandler: ->
    @loadPostsFromServer()

  handleSearchSubmit: (keyword) ->
    @loadPostsFromServer(keyword)

  handlePageChange: (page) ->
    @loadPostsFromServer(@state.keyword, page)

  render: ->
    return `(
      <QueueRows onChange={this.changeHandler} onSearchSubmit={this.handleSearchSubmit}  onPageChange={this.handlePageChange} config={this.state.config} data={this.state.data} />
    )`
)

ReactDOM.render `<QueueList pollInterval={60000} />`, document.getElementById('tableController')
