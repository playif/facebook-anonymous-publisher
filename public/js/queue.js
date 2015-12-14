var ActionButton, Pagination, QueueList, QueueRows, SearchForm;

QueueRows = React.createClass({displayName: "QueueRows",
  convertTimestamp: function(timestamp) {
    var date, day, hours, minutes, month, seconds, time, year;
    date = new Date(timestamp * 1000);
    year = date.getFullYear();
    month = '0' + (date.getMonth() + 1);
    day = '0' + date.getDate();
    hours = '0' + date.getHours();
    minutes = '0' + date.getMinutes();
    seconds = '0' + date.getSeconds();
    return time = year + '-' + month.substr(-2) + '-' + day.substr(-2) + ' ' + hours.substr(-2) + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
  },
  getPostTypeText: function(type) {
    var text;
    switch (type) {
      case 1:
        text = this.props.config.plaintext;
        break;
      case 2:
        text = this.props.config.link;
        break;
      case 3:
        text = this.props.config.image;
    }
    return text;
  },
  getPostStateText: function(state) {
    switch (state) {
      case 0:
        state = this.props.config.queuing;
        break;
      case 1:
        state = this.props.config.published;
        break;
      case 2:
        state = this.props.config.unpublished;
        break;
      case 4:
        state = this.props.config.deny;
        break;
      case 5:
        state = this.props.config.pending;
        break;
      case 8:
        state = this.props.config.idle;
        break;
      case 9:
        state = this.props.config.failed;
    }
    return state;
  },
  getActionButton: function(id, state) {
    var style, type;
    if (state === 5) {
      return React.createElement("div", null, React.createElement("div", {className: "btn-group", role: "group"}, React.createElement(ActionButton, {config: this.props.config, onClick: this.handleButtonClick.bind(this, id, 'deny'), id: id, type: 'deny', style: 'danger'}), React.createElement(ActionButton, {config: this.props.config, onClick: this.handleButtonClick.bind(this, id, 'allow'), id: id, type: 'allow', style: 'success'})), " ", React.createElement(ActionButton, {config: this.props.config, onClick: this.handleButtonClick.bind(this, id, 'block'), type: 'block', style: 'default'}));
    } else {
      style = 'default';
      switch (state) {
        case 0:
          type = 'deny';
          style = 'danger';
          break;
        case 1:
          type = 'unpublish';
          break;
        case 2:
        case 4:
        case 8:
        case 9:
          type = 'republish';
          style = 'success';
      }
      return React.createElement("div", null, React.createElement(ActionButton, {config: this.props.config, onClick: this.handleButtonClick.bind(this, id, type), type: type, style: style}), " ", React.createElement(ActionButton, {config: this.props.config, onClick: this.handleButtonClick.bind(this, id, 'block'), type: 'block', style: 'default'}));
    }
  },
  getRowClassName: function(state) {
    switch (state) {
      case 0:
        state = 'queuing';
        break;
      case 1:
        state = 'published';
        break;
      case 2:
        state = 'unpublished';
        break;
      case 4:
        state = 'deny';
        break;
      case 5:
        state = 'pending';
        break;
      case 8:
        state = 'idle';
        break;
      case 9:
        state = 'failed';
    }
    return 'state-' + state;
  },
  handleButtonClick: function(id, type) {
    var check;
    if (type === 'block') {
      check = confirm('Are you sure?');
      if (!check) {
        return;
      }
    }
    return $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: '/admin/action/' + type + '/' + id,
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
          message: 'Something wrong.'
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
  handleSearchSubmit: function(keyword) {
    return this.props.onSearchSubmit(keyword);
  },
  handlePageChange: function(page) {
    return this.props.onPageChange(page);
  },
  handleRefreshButtonClick: function() {
    return this.props.onChange();
  },
  render: function() {
    var _, postNodes;
    if (this.props.data.result !== void 0 && this.props.data.result.length) {
      _ = this;
      postNodes = this.props.data.result.map(function(post) {
        var action, classname, date, state, type;
        type = _.getPostTypeText(post.post_type);
        state = _.getPostStateText(post.post_state);
        date = _.convertTimestamp(post.insert_time);
        action = _.getActionButton(post.id, post.post_state);
        classname = _.getRowClassName(post.post_state);
        return (
          React.createElement("tr", {className: classname, key: post.id}, 
            React.createElement("td", {className: "text-center"}, React.createElement("a", {href: '/' + post.post_key, target: "_blank"}, post.id)), 
            React.createElement("td", {className: "text-center"}, state), 
            React.createElement("td", {className: "text-center"}, type), 
            React.createElement("td", {className: "td-message"}, React.createElement("p", {dangerouslySetInnerHTML: {__html: post.post_message}})), 
            React.createElement("td", {className: "text-center"}, date), 
            React.createElement("td", {className: "text-center td-action"}, action)
          )
        );
      });
    } else {
      postNodes = (
        React.createElement("tr", {key: "null"}, 
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
              React.createElement("h4", {className: "pull-left"}, 
                this.props.config.queue_list, "  ", 
                React.createElement("small", {className: "glyphicon glyphicon-refresh refresh-button", onClick: this.handleRefreshButtonClick})
              ), 
              React.createElement(SearchForm, {onSearchSubmit: this.handleSearchSubmit, config: this.props.config})
            )
          ), 
          React.createElement("tr", null, 
            React.createElement("td", null, this.props.config.id), 
            React.createElement("td", null, this.props.config.state), 
            React.createElement("td", null, this.props.config.type), 
            React.createElement("td", {className: "td-message"}, this.props.config.message), 
            React.createElement("td", null, this.props.config.insert_time), 
            React.createElement("td", null, this.props.config.actions)
          )
        ), 
        React.createElement("tbody", {className: "queue-row"}, 
          postNodes
        ), 
        React.createElement("tfoot", null, 
          React.createElement("tr", null, 
            React.createElement("td", {className: "text-center", colSpan: "6"}, 
              React.createElement(Pagination, {data: this.props.data, onPageChange: this.handlePageChange})
            )
          )
        )
      )
    );
  }
});

ActionButton = React.createClass({displayName: "ActionButton",
  render: function() {
    var text;
    text = this.props.config[this.props.type];
    return (
      React.createElement("button", {type: "button", onClick: this.props.onClick, className: 'btn btn-sm btn-' + this.props.style}, text)
    );
  }
});

Pagination = React.createClass({displayName: "Pagination",
  handleClick: function(page) {
    return this.props.onPageChange(page);
  },
  render: function() {
    var i, j, pages, ref;
    pages = [];
    if (this.props.data.last_page > 1) {
      for (i = j = 1, ref = this.props.data.last_page; 1 <= ref ? j <= ref : j >= ref; i = 1 <= ref ? ++j : --j) {
        if (i === this.props.data.current_page) {
          pages.push(React.createElement("li", {className: "active", key: i}, React.createElement("a", {href: "#"}, i)));
        } else {
          pages.push(React.createElement("li", {key: i}, React.createElement("a", {href: "#", onClick: this.handleClick.bind(this, i)}, i)));
        }
      }
    } else {
      pages.push(React.createElement("li", {className: "active", key: '1'}, React.createElement("a", {href: "#"}, "1")));
    }
    return (
      React.createElement("nav", null, 
        React.createElement("ul", {className: "pagination"}, 
          pages
        )
      )
    );
  }
});

SearchForm = React.createClass({displayName: "SearchForm",
  handleSubmit: function(e) {
    var keyword;
    e.preventDefault();
    keyword = React.findDOMNode(this.refs.keyword).value.trim();
    return this.props.onSearchSubmit({
      keyword: keyword
    });
  },
  render: function() {
    return (
      React.createElement("form", {className: "form-inline pull-right"}, 
        React.createElement("div", {className: "input-group"}, 
          React.createElement("input", {type: "text", ref: "keyword", className: "form-control", placeholder: this.props.config.search, onKeyUp: this.handleSubmit})
        )
      )
    );
  }
});

QueueList = React.createClass({displayName: "QueueList",
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
  loadPostsFromServer: function(keyword, page) {
    if (page == null) {
      page = 1;
    }
    return $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: '/admin/ajax/queue.json?page=' + page,
      dataType: 'json',
      type: 'post',
      data: keyword,
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
      keyword: '',
      config: [],
      data: []
    };
  },
  componentDidMount: function() {
    this.loadConfigFromServer();
    this.loadPostsFromServer();
    return setInterval(this.loadPostsFromServer, this.props.pollInterval);
  },
  changeHandler: function() {
    return this.loadPostsFromServer();
  },
  handleSearchSubmit: function(keyword) {
    return this.loadPostsFromServer(keyword);
  },
  handlePageChange: function(page) {
    return this.loadPostsFromServer(this.state.keyword, page);
  },
  render: function() {
    return (
      React.createElement(QueueRows, {onChange: this.changeHandler, onSearchSubmit: this.handleSearchSubmit, onPageChange: this.handlePageChange, config: this.state.config, data: this.state.data})
    );
  }
});

ReactDOM.render(React.createElement(QueueList, {pollInterval: 60000}), document.getElementById('tableController'));
