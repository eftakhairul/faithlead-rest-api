set :stages, %w(staging production dev qa)
set :default_stage, "dev"
set :stage_dir,     "app/config/deploy"
require 'capistrano/ext/multistage'
require 'colored'
default_run_options[:pty] = true

set :application, "dev.faithlead.com"
set :user, "dev"
set :password, "faithlead123"
set :port, 22
set :use_sudo, false
set :group_writable, false

set :symfony_env_prod, "dev"

set :model_manager, "doctrine"
set :interactive_mode, false

set :repository, "git@bitbucket.org:faithlead/repo.git"
set :scm_username, "sas05"
set :scm, :git
set :branch, "develop"
# set :tag, 'v1.01-dev-maintenance'
set :runner, user
set :deploy_via, :checkout

set :use_composer, true

set :keep_releases, 3

logger.level = Logger::MAX_LEVEL

task :uname do
  run "uname -a"
end

namespace :deploy do
  task :start do
    ;
  end
  task :stop do
    ;
  end
  task :restart do
    ;
  end
end



#after 'deploy:update', 'syncmedia:symlink_thumbs'
#after 'deploy:update', 'syncmedia:symlink_upload'
#after 'deploy:update', 'syncmedia:create_proxy_dir'
#after "deploy:update", "deploy:cleanup"
