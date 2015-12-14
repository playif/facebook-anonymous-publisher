QueueRows = React.createClass(
  getActionButton: (id) ->
    return `(
      <ActionButton config={this.props.config} onClick={this.handleButtonClick.bind(this, id)} />
    )`

  handleButtonClick: (id, type)->
    $.ajax
      headers: 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      url: '/admin/action/delete/block_keyword/' + id
      dataType: 'json'
      type: 'post'
      cache: false
      success: ((result) ->
        @props.onChange()
        type = if result.state is 'success' then 'success' else 'danger'
        $.notify { message: result.message }, { placement: { from: "bottom" }, type: type}
      ).bind(@)
      error: ((xhr, status, err) ->
        $.notify { message: 'Failed.' }, { placement: { from: "bottom" }, type: 'danger'}
        console.error @props.url, status, err.toString()
      ).bind(@)

  handleKeywordSubmit: (keyword)->
    $.ajax
      headers: 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      url: '/admin/action/add-keyword'
      dataType: 'json'
      type: 'post'
      data: keyword
      cache: false
      success: ((result) ->
        @props.onChange()
        type = if result.state is 'success' then 'success' else 'danger'
        $.notify { message: result.message }, { placement: { from: "bottom" }, type: type}
      ).bind(@)
      error: ((xhr, status, err) ->
        $.notify { message: 'Failed.' }, { placement: { from: "bottom" }, type: 'danger'}
        console.error @props.url, status, err.toString()
      ).bind(@)

  render: ->
    if @props.data.length
      _ = @
      rowNodes = @props.data.map((item) ->
        action = _.getActionButton item.id
        return `(
          <tr>
            <td className="text-center">{item.id}</td>
            <td className="text-center">{item.keyword}</td>
            <td className="text-center">{action}</td>
          </tr>
        )`
      )
    else
      rowNodes = `(
        <tr>
          <td className="text-center td-empty" colSpan="6">
            {this.props.config.empty}
          </td>
        </tr>
      )`

    return `(
      <table className="table table-hover table-bordered">
        <thead>
          <tr>
            <td colSpan="6">
              <h4 className="pull-left">
                {this.props.config.block_keyword_list}
              </h4>
              <AddForm onKeywordSubmit={this.handleKeywordSubmit} config={this.props.config}  />
            </td>
          </tr>
          <tr className="text-center">
            <td>{this.props.config.id}</td>
            <td>{this.props.config.keyword}</td>
            <td>{this.props.config.actions}</td>
          </tr>
        </thead>
        <tbody className="queue-row">
          {rowNodes}
        </tbody>
      </table>
    )`
)

AddForm = React.createClass(
  handleSubmit: (e) ->
    e.preventDefault()
    keyword = React.findDOMNode(@refs.keyword).value.trim()
    if !keyword
      return
    @props.onKeywordSubmit
      keyword: keyword
    React.findDOMNode(@refs.keyword).value = ''

  render: ->
    return `(
      <form className="form-inline pull-right" onSubmit={this.handleSubmit}>
        <div className="input-group">
          <input type="text" ref="keyword" className="form-control" placeholder={this.props.config.keyword} />
          <span className="input-group-btn">
            <button type="submit" className="btn btn-danger">{this.props.config.add_keyword}</button>
          </span>
        </div>
      </form>
    )`
)

ActionButton = React.createClass(
  render: ->
    text = @props.config['delete']
    return `(
      <button type="button" onClick={this.props.onClick} className={'btn btn-sm btn-default'}>{text}</button>
    )`
)

BlockList = React.createClass(
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

  loadListFromServer: ->
    $.ajax
      headers: 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      url: '/admin/ajax/block-keyword.json'
      dataType: 'json'
      type: 'post'
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
      config: []
      data: []
    }

  componentDidMount: ->
    @loadConfigFromServer()
    @loadListFromServer()
    setInterval @loadListFromServer, @props.pollInterval

  changeHandler: ->
    @loadListFromServer()

  render: ->
    return `(
      <QueueRows onChange={this.changeHandler} config={this.state.config} data={this.state.data} />
    )`
)

ReactDOM.render `<BlockList pollInterval={600000} />`, document.getElementById('tableController')
