var ActionButton, BlockList, QueueRows;

QueueRows = React.createClass({displayName: "QueueRows",
  getActionButton: function(id) {
    return (
      React.createElement(ActionButton, {config: this.props.config, onClick: this.handleButtonClick.bind(this, id)})
    );
  },
  handleButtonClick: function(id, type) {
    return $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: '/admin/action/delete/block_guest/' + id,
      dataType: 'json',
      type: 'post',
      cache: false,
      success: (function(result) {
        this.props.onChange();
        type = result.state === 'success' ? 'success' : 'danger';
        return $.notify({
          message: result.message
        }, {
          placement: {
            from: "bottom"
          },
          type: type
        });
      }).bind(this),
      error: (function(xhr, status, err) {
        $.notify({
          message: 'Failed.'
        }, {
          placement: {
            from: "bottom"
          },
          type: 'danger'
        });
        return console.error(this.props.url, status, err.toString());
      }).bind(this)
    });
  },
  render: function() {
    var _, rowNodes;
    if (this.props.data.length) {
      _ = this;
      rowNodes = this.props.data.map(function(item) {
        var action;
        action = _.getActionButton(item.id);
        return (
          React.createElement("tr", null, 
            React.createElement("td", {className: "text-center"}, item.id), 
            React.createElement("td", {className: "text-center"}, item.ip), 
            React.createElement("td", {className: "text-center"}, action)
          )
        );
      });
    } else {
      rowNodes = (
        React.createElement("tr", null, 
          React.createElement("td", {className: "text-center td-empty", colSpan: "6"}, 
            this.props.config.empty
          )
        )
      );
    }
    return (
      React.createElement("table", {className: "table table-hover table-bordered"}, 
        React.createElement("thead", {className: "text-center"}, 
          React.createElement("tr", null, 
            React.createElement("td", {colSpan: "6"}, 
              React.createElement("h4", null, this.props.config.block_guest_list)
            )
          ), 
          React.createElement("tr", null, 
            React.createElement("td", null, this.props.config.id), 
            React.createElement("td", null, this.props.config.ip), 
            React.createElement("td", null, this.props.config.actions)
          )
        ), 
        React.createElement("tbody", {className: "queue-row"}, 
          rowNodes
        )
      )
    );
  }
});

ActionButton = React.createClass({displayName: "ActionButton",
  render: function() {
    var text;
    text = this.props.config['delete'];
    return (
      React.createElement("button", {type: "button", onClick: this.props.onClick, className: 'btn btn-sm btn-default'}, text)
    );
  }
});

BlockList = React.createClass({displayName: "BlockList",
  loadConfigFromServer: function() {
    return $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: '/admin/ajax/config.json',
      dataType: 'json',
      type: 'post',
      cache: false,
      success: (function(config) {
        return this.setState({
          config: config
        });
      }).bind(this),
      error: (function(xhr, status, err) {
        $.notify({
          message: 'Something wrong. ' + err.toString()
        }, {
          placement: {
            from: "bottom"
          },
          type: 'danger'
        });
        return console.error(this.props.url, status, err.toString());
      }).bind(this)
    });
  },
  loadListFromServer: function() {
    return $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: '/admin/ajax/block-guest.json',
      dataType: 'json',
      type: 'post',
      cache: false,
      success: (function(data) {
        return this.setState({
          data: data
        });
      }).bind(this),
      error: (function(xhr, status, err) {
        $.notify({
          message: 'Something wrong. ' + err.toString()
        }, {
          placement: {
            from: "bottom"
          },
          type: 'danger'
        });
        return console.error(this.props.url, status, err.toString());
      }).bind(this)
    });
  },
  getInitialState: function() {
    return {
      config: [],
      data: []
    };
  },
  componentDidMount: function() {
    this.loadConfigFromServer();
    this.loadListFromServer();
    return setInterval(this.loadListFromServer, this.props.pollInterval);
  },
  changeHandler: function() {
    return this.loadListFromServer();
  },
  render: function() {
    return (
      React.createElement(QueueRows, {onChange: this.changeHandler, config: this.state.config, data: this.state.data})
    );
  }
});

ReactDOM.render(React.createElement(BlockList, {pollInterval: 600000}), document.getElementById('tableController'));
