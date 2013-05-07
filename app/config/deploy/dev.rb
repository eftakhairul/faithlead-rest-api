server '184.106.81.241', :app, :web, :primary => true

set :deploy_to, "/home/dev/public_html/dev.faithlead.com/code/"
set :branch, "develop"

set :password, "faithlead123"
set :port, 22
set :use_sudo, false