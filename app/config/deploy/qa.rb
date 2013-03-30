server 'qa.okaydoit.com', :app, :web, :primary => true

set :deploy_to, "/home/dev/public_html/qa.okaydoit.com/code"
set :branch, "develop"